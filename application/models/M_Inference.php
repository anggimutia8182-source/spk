<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Inference extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->model('M_Rule');
        $this->load->model('M_Gejala');
        $this->load->model('M_Penyakit');
    }

    /**
     * Run Forward Chaining inference based on selected symptoms
     * 
     * @param array $selected_gejala Array of symptom codes, e.g. ['G01', 'G02', 'G14']
     * @return array Comprehensive inference result and trace
     */
    public function forward_chaining($selected_gejala = []) {
        if (!is_array($selected_gejala)) {
            $selected_gejala = [];
        }

        // Clean and normalize input
        $selected_gejala = array_unique(array_filter(array_map('trim', $selected_gejala)));
        sort($selected_gejala);

        // Fetch symptom details for selected symptoms
        $input_symptoms = $this->M_Gejala->get_by_codes($selected_gejala);

        // Fetch all active rules
        $rules = $this->M_Rule->get_all();

        // Working memory initialized with selected facts
        $working_memory = $selected_gejala;
        $derived_facts = [];

        $steps = [];
        $evaluations = [];
        $fired_rules = [];
        $highest_partial = null;
        $max_partial_percentage = 0;

        $step_number = 1;

        // Trace initial working memory
        $steps[] = [
            'step' => $step_number++,
            'working_memory' => implode(', ', $working_memory) ?: '(Tidak ada gejala dipilih)',
            'rule_tested' => 'Inisialisasi Fakta Masukan (Working Memory)',
            'condition_met' => '-',
            'new_facts' => 'Fakta awal tercatat: ' . count($working_memory) . ' gejala'
        ];

        // Evaluate each rule
        foreach ($rules as $rule) {
            $required = $rule->gejala_codes; // e.g. ['G01', 'G02', 'G14']
            $matched = array_values(array_intersect($required, $working_memory));
            $missing = array_values(array_diff($required, $working_memory));

            $total_required = count($required);
            $total_matched = count($matched);
            $percentage = $total_required > 0 ? round(($total_matched / $total_required) * 100, 1) : 0;

            $is_fired = ($total_required > 0 && $total_matched === $total_required);

            if ($is_fired) {
                if (!in_array($rule->kode_penyakit, $derived_facts)) {
                    $derived_facts[] = $rule->kode_penyakit;
                }
                $fired_rules[] = [
                    'rule' => $rule,
                    'matched_gejala' => $matched,
                    'percentage' => 100
                ];
            }

            // Track highest partial match if not 100%
            if (!$is_fired && $percentage > $max_partial_percentage) {
                $max_partial_percentage = $percentage;
                $highest_partial = [
                    'rule' => $rule,
                    'matched_gejala' => $matched,
                    'missing_gejala' => $missing,
                    'percentage' => $percentage
                ];
            }

            // Record evaluation
            $evaluations[] = [
                'id_rule' => $rule->id_rule,
                'kode_penyakit' => $rule->kode_penyakit,
                'nama_penyakit' => $rule->nama_penyakit,
                'saran_penanganan' => $rule->saran_penanganan,
                'required_gejala' => $required,
                'matched_gejala' => $matched,
                'missing_gejala' => $missing,
                'total_required' => $total_required,
                'total_matched' => $total_matched,
                'percentage' => $percentage,
                'is_fired' => $is_fired,
                'if_condition' => implode(' AND ', $required),
                'then_result' => $rule->nama_penyakit . ' (' . $rule->kode_penyakit . ')'
            ];

            // Trace step for this rule
            $steps[] = [
                'step' => $step_number++,
                'working_memory' => implode(', ', array_merge($working_memory, $derived_facts)),
                'rule_tested' => $rule->id_rule . ': ' . implode(' AND ', $required) . ' &rarr; ' . $rule->nama_penyakit,
                'condition_met' => $is_fired ? 'Ya (Semua gejala terpenuhi)' : 'Tidak (' . $total_matched . '/' . $total_required . ' terpenuhi)',
                'new_facts' => $is_fired ? '+ ' . $rule->kode_penyakit . ' (' . $rule->nama_penyakit . ')' : '-'
            ];
        }

        // Final trace step
        $final_fact_text = !empty($derived_facts) 
            ? 'Hasil akhir: ' . implode(', ', array_map(function($f) {
                $p = $this->M_Penyakit->get_by_kode($f);
                return $p ? $p->nama_penyakit : $f;
            }, $derived_facts))
            : 'Tidak ada rule baru yang dieksekusi -> Selesai';

        $steps[] = [
            'step' => $step_number,
            'working_memory' => implode(', ', array_merge($working_memory, $derived_facts)),
            'rule_tested' => 'Proses Pelacakan Selesai (STOP)',
            'condition_met' => '-',
            'new_facts' => $final_fact_text
        ];

        // Determine final diagnosis
        $diagnosed_penyakit = [];
        $primary_diagnosa = null;

        if (!empty($derived_facts)) {
            foreach ($derived_facts as $kode_p) {
                $p = $this->M_Penyakit->get_by_kode($kode_p);
                if ($p) {
                    $diagnosed_penyakit[] = $p;
                }
            }
            $primary_diagnosa = $diagnosed_penyakit[0];
        }

        return [
            'selected_gejala' => $selected_gejala,
            'input_symptoms' => $input_symptoms,
            'working_memory' => $working_memory,
            'derived_facts' => $derived_facts,
            'evaluations' => $evaluations,
            'fired_rules' => $fired_rules,
            'steps' => $steps,
            'diagnosed_penyakit' => $diagnosed_penyakit,
            'primary_diagnosa' => $primary_diagnosa,
            'is_identified' => !empty($diagnosed_penyakit),
            'highest_partial' => $highest_partial,
            'total_rules_evaluated' => count($rules),
            'total_rules_fired' => count($fired_rules)
        ];
    }
}

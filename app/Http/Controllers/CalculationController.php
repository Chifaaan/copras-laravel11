<?php

namespace App\Http\Controllers;

use App\Models\Calculation;
use Illuminate\Http\Request;

class CalculationController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function calculate(Request $request)
    {
        $criteria = [];
        $types = [];
        $weights = [];

        for ($i = 1; $i <= 5; $i++) {
            $criteria[] = $request->input("kriteria$i");
            $types[] = $request->input("type$i");
            $weights[] = $request->input("bobot$i");
        }

        // Menghitung jumlah alternatif yang diinput oleh user
        $num_alternatives = 0;
        while ($request->input("nama_alternatif" . ($num_alternatives + 1))) {
            $num_alternatives++;
        }

        $alternatives = [];
        for ($i = 1; $i <= $num_alternatives; $i++) {
            $alternative = [];
            for ($j = 1; $j <= 5; $j++) {
                $alternative[] = $request->input("alt{$i}_krit{$j}");
            }
            $alternatives[] = $alternative;
        }

        // Langkah 1: Normalisasi
        $normalized = $this->normalize($alternatives, $num_alternatives, 5);

        // Langkah 2: Pembobotan
        $weighted = $this->weighted($normalized, $weights, $num_alternatives, 5);

        // Langkah 3: Perhitungan Jumlah Benefit dan Cost
        list($benefit_sums, $cost_sums) = $this->calculateSums($weighted, $types, $num_alternatives, 5);

        // Langkah 4: Perhitungan Q
        $Q = $this->calculateQ($benefit_sums, $cost_sums, $num_alternatives);

        arsort($Q);

        $results = [];
        $rank = 1;
        foreach ($Q as $alt => $q_value) {
            $results[] = [
                'alternative' => $request->input("nama_alternatif" . ($alt + 1)),
                'qi' => $q_value,
                'rank' => $rank++
            ];
        }

        // Simpan perhitungan ke dalam database
        $calculation = Calculation::create([
            'criteria' => json_encode(compact('criteria', 'types', 'weights')),
            'alternatives' => json_encode($alternatives),
            'results' => json_encode($results),
            'steps' => json_encode([
                'normalized' => $normalized,
                'weighted' => $weighted,
                'benefit_sums' => $benefit_sums,
                'cost_sums' => $cost_sums,
                'Q' => $Q
            ])
        ]);

        return view('results', [
            'results' => $results,
            'steps' => json_decode($calculation->steps, true)
        ]);
    }

    public function show($id)
    {
        $calculation = Calculation::findOrFail($id);
        $results = json_decode($calculation->results, true);

        return view('results', ['results' => $results]);
    }

    public function history()
    {
        $calculations = Calculation::all();
        return view('history', ['calculations' => $calculations]);
    }

    public function destroy($id)
    {
        Calculation::destroy($id);
        return redirect()->route('history');
    }

    public function recalculate($id)
    {
        $calculation = Calculation::findOrFail($id);
        $criteria = json_decode($calculation->criteria, true);
        $alternatives = json_decode($calculation->alternatives, true);

        $num_alternatives = count($alternatives);

        $normalized = $this->normalize($alternatives, $num_alternatives, 5);
        $weighted = $this->weighted($normalized, $criteria['weights'], $num_alternatives, 5);
        list($benefit_sums, $cost_sums) = $this->calculateSums($weighted, $criteria['types'], $num_alternatives, 5);
        $Q = $this->calculateQ($benefit_sums, $cost_sums, $num_alternatives);

        arsort($Q);

        $results = [];
        $rank = 1;
        foreach ($Q as $alt => $q_value) {
            $results[] = [
                'alternative' => 'Alternatif ' . ($alt + 1),
                'qi' => $q_value,
                'rank' => $rank++
            ];
        }

        $calculation->results = json_encode($results);
        $calculation->save();

        return view('results', ['results' => $results]);
    }

    private function normalize($matrix, $num_alternatives, $num_criteria)
    {
        $normalized = array();
        for ($j = 0; $j < $num_criteria; $j++) {
            $sum = 0;
            for ($i = 0; $i < $num_alternatives; $i++) {
                $sum += $matrix[$i][$j];
            }
            for ($i = 0; $i < $num_alternatives; $i++) {
                $normalized[$i][$j] = $matrix[$i][$j] / $sum;
            }
        }
        return $normalized;
    }

    private function weighted($normalized, $weights, $num_alternatives, $num_criteria)
    {
        $weighted = array();
        for ($i = 0; $i < $num_alternatives; $i++) {
            for ($j = 0; $j < $num_criteria; $j++) {
                $weighted[$i][$j] = $normalized[$i][$j] * $weights[$j];
            }
        }
        return $weighted;
    }

    private function calculateSums($weighted, $types, $num_alternatives, $num_criteria)
    {
        $benefit_sums = array();
        $cost_sums = array();
        for ($i = 0; $i < $num_alternatives; $i++) {
            $benefit_sums[$i] = 0;
            $cost_sums[$i] = 0;
            for ($j = 0; $j < $num_criteria; $j++) {
                if ($types[$j] == 'benefit') {
                    $benefit_sums[$i] += $weighted[$i][$j];
                } else {
                    $cost_sums[$i] += $weighted[$i][$j];
                }
            }
        }
        return array($benefit_sums, $cost_sums);
    }

    private function calculateQ($benefit_sums, $cost_sums, $num_alternatives)
    {
        $Q = array();
        $min_cost = min($cost_sums);
        $sum_costs = array_sum($cost_sums);
        $sum_min_cost_ratios = 0;

        // Menghitung jumlah min(S^-) / S_n^-
        for ($i = 0; $i < $num_alternatives; $i++) {
            $sum_min_cost_ratios += $min_cost / $cost_sums[$i];
        }

        // Menghitung Qi
        for ($i = 0; $i < $num_alternatives; $i++) {
            $Q[$i] = $benefit_sums[$i] + ($min_cost * $sum_costs) / ($cost_sums[$i] * $sum_min_cost_ratios);
        }

        return $Q;
    }
}

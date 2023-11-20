<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class HasilController extends Controller
{
    // public function getHasil($studentId)
    // {
    //     // Ambil data siswa berdasarkan ID
    //     $student = Student::findOrFail($studentId);

    //     // Ambil hasil ujian siswa
    //     $results = $student->mapels()->withPivot('nilai')->get();

    //     // Format data untuk ditampilkan
    //     $formattedResults = [];

    //     foreach ($results as $result) {
    //         $formattedResults[] = [
    //             'ujian' => $result->ujian,
    //             'nilai' => $result->pivot->nilai,
    //         ];
    //     }

    //     return response()->json([
    //         'student' => $student->toArray(),
    //         'results' => $formattedResults,
    //     ]);

    //     // Ambil data siswa berdasarkan ID

    // }

    // public function getHasil($studentId)
    // {
    //     // Ambil data siswa berdasarkan ID
    //     $student = Student::findOrFail($studentId);

    //     // Ambil hasil ujian siswa dengan nilai
    //     $results = $student->mapels()->withPivot('nilai')->get();

    //     // Format data untuk ditampilkan
    //     $formattedResults = [];

    //     foreach ($results as $result) {
    //         // Check if 'nilai' is an integer
    //         if (is_numeric($result->pivot->nilai)) {
    //             // Handle the case where 'nilai' is an integer
    //             $formattedResults[] = [
    //                 'ujian' => $result->ujian,
    //                 'nilai' => $result->pivot->nilai,
    //             ];
    //         } else {
    //             // Handle the case where 'nilai' is a string (not JSON)
    //             $formattedResults[] = [
    //                 'ujian' => $result->ujian,
    //                 'error' => 'Invalid data type in nilai field. Expected integer.',
    //             ];
    //         }
    //     }

    //     // Calculate the total nilai akhir
    //     $totalNilaiAkhir = array_sum(array_column($formattedResults, 'nilai'));

    //     return response()->json([
    //         'student' => $student->toArray(),
    //         'results' => $formattedResults,
    //         'total_nilai_akhir' => $totalNilaiAkhir,
    //     ]);
    // }
    public function getHasil($studentId)
    {
        // Ambil data siswa berdasarkan ID
        $student = Student::findOrFail($studentId);

        // Ambil hasil ujian siswa dengan nilai
        $results = $student->mapels()->withPivot('nilai')->get();

        // Format data untuk ditampilkan
        $formattedResults = [];

        foreach ($results as $result) {
            // Check if 'nilai' is an integer
            if (is_numeric($result->pivot->nilai)) {
                // Handle the case where 'nilai' is an integer
                $formattedResults[] = [
                    'ujian' => $result->ujian,
                    'nilai' => $result->pivot->nilai,
                ];
            } else {
                // Handle the case where 'nilai' is a string (not JSON)
                $formattedResults[] = [
                    'ujian' => $result->ujian,
                    'error' => 'Invalid data type in nilai field. Expected integer.',
                ];
            }
        }

        // Calculate the total nilai akhir based on the provided formula
        $totalNilaiAkhir = 0;
        foreach ($formattedResults as $result) {
            switch ($result['ujian']) {
                case 'HARIAN':
                    $totalNilaiAkhir += $result['nilai'] * 10 / 100;
                    break;
                case 'UTS':
                    $totalNilaiAkhir += $result['nilai'] * 30 / 100;
                    break;
                case 'UAS':
                    $totalNilaiAkhir += $result['nilai'] * 60 / 100;
                    break;
                // Add more cases if needed
            }
        }

        return response()->json([
            'student' => $student->toArray(),
            'results' => $formattedResults,
            'total_nilai_akhir' => $totalNilaiAkhir,
        ]);
    }



    public function getStudent()
    {
        $students = Student::all();

        return response()->json([
            'students' => $students
        ]);
    }

}

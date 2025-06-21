<?php

namespace Database\Seeders;

use App\Models\TreatmentType;
use Illuminate\Database\Seeder;

class TreatmentTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TreatmentType::truncate();

        TreatmentType::insert([
            ['name' => json_encode([
                'pt' => 'Alimentação',
                'en' => 'Feeding',
            ])],
            ['name' => json_encode([
                'pt' => 'Analises ao Sangue',
                'en' => 'Blood Analyzes',
            ])],
            ['name' => json_encode([
                'pt' => 'Analises Citologicas',
                'en' => 'Cytological Analyzes',
            ])],
            ['name' => json_encode([
                'pt' => 'Analises Dermatologicas',
                'en' => 'Dermatological Analyzes',
            ])],
            ['name' => json_encode([
                'pt' => 'Biopsia',
                'en' => 'Biopsy',
            ])],
            ['name' => json_encode([
                'pt' => 'Castração',
                'en' => 'Castration',
            ])],
            ['name' => json_encode([
                'pt' => 'Castração Cria',
                'en' => 'Castration Creates',
            ])],
            ['name' => json_encode([
                'pt' => 'Castração Testículos Inclusos',
                'en' => 'Castration Testicles Included',
            ])],
            ['name' => json_encode([
                'pt' => 'Chip',
                'en' => 'Chip',
            ])],
            ['name' => json_encode([
                'pt' => 'Cirurgia',
                'en' => 'Surgery',
            ])],
            ['name' => json_encode([
                'pt' => 'Cirurgia Ortopédica',
                'en' => 'Orthopedic Surgery',
            ])],
            ['name' => json_encode([
                'pt' => 'Consulta',
                'en' => 'Appointment',
            ])],
            ['name' => json_encode([
                'pt' => 'Convénia',
                'en' => 'Convenza',
            ])],
            ['name' => json_encode([
                'pt' => 'Corte na Orelha',
                'en' => 'Ear Cut',
            ])],
            ['name' => json_encode([
                'pt' => 'Desparasitação Externa',
                'en' => 'External Deworming',
            ])],
            ['name' => json_encode([
                'pt' => 'Desparasitação Interna',
                'en' => 'Internal Deworming',
            ])],
            ['name' => json_encode([
                'pt' => 'Desparasitação Interna e Externa',
                'en' => 'Internal and External Deworming',
            ])],
            ['name' => json_encode([
                'pt' => 'Destartarização',
                'en' => 'Detartarization',
            ])],
            ['name' => json_encode([
                'pt' => 'Eco',
                'en' => 'Echo',
            ])],
            ['name' => json_encode([
                'pt' => 'Eutanásia',
                'en' => 'Euthanasia',
            ])],
            ['name' => json_encode([
                'pt' => 'Extração da Cadeia Mamaria',
                'en' => 'Breast Extraction',
            ])],
            ['name' => json_encode([
                'pt' => 'Extração de Dentes',
                'en' => 'Tooth Extraction',
            ])],
            ['name' => json_encode([
                'pt' => 'Extração do Olho',
                'en' => 'Eye Extraction',
            ])],
            ['name' => json_encode([
                'pt' => 'Fisioterapia',
                'en' => 'Physiotherapy',
            ])],
            ['name' => json_encode([
                'pt' => 'Internamento',
                'en' => 'Hospitalization',
            ])],
            ['name' => json_encode([
                'pt' => 'Leishmaniose',
                'en' => 'Leishmaniasis',
            ])],
            ['name' => json_encode([
                'pt' => 'Limpeza Cirúrgica de Ferida',
                'en' => 'Surgical Wound Cleaning',
            ])],
            ['name' => json_encode([
                'pt' => 'Limpeza de Ferimento',
                'en' => 'Injury Cleansing',
            ])],
            ['name' => json_encode([
                'pt' => 'Medicação',
                'en' => 'Medication',
            ])],
            ['name' => json_encode([
                'pt' => 'OVH',
                'en' => 'OVH',
            ])],
            ['name' => json_encode([
                'pt' => 'OVH Cria',
                'en' => 'OVH Creates',
            ])],
            ['name' => json_encode([
                'pt' => 'OVH Piómetra',
                'en' => 'OVH Piómetra',
            ])],
            ['name' => json_encode([
                'pt' => 'OVH Prenhe',
                'en' => 'OVH Fill',
            ])],
            ['name' => json_encode([
                'pt' => 'Raio X',
                'en' => 'X-ray',
            ])],
            ['name' => json_encode([
                'pt' => 'Sarna',
                'en' => 'Scabies',
            ])],
            ['name' => json_encode([
                'pt' => 'Sedação',
                'en' => 'Sedation',
            ])],
            ['name' => json_encode([
                'pt' => 'Soro',
                'en' => 'Serum',
            ])],
            ['name' => json_encode([
                'pt' => 'Teste Fiv/Felv',
                'en' => 'Fiv / Felv Test',
            ])],
            ['name' => json_encode([
                'pt' => 'Tinha (Dermatofitose)',
                'en' => 'Dermatophytosis',
            ])],
            ['name' => json_encode([
                'pt' => 'Transfusão de Sangue',
                'en' => 'Blood Transfusion',
            ])],
            ['name' => json_encode([
                'pt' => 'Vacinação',
                'en' => 'Vaccination',
            ])],
        ]);
    }
}

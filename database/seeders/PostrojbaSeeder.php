<?php

namespace Database\Seeders;

use App\Models\Jls;
use App\Models\Postrojba;
use Illuminate\Database\Seeder;

class PostrojbaSeeder extends Seeder
{
    public function run(): void
    {
        // Dohvati JLS-ove po nazivu (mapping)
        $jlsMap = Jls::pluck('id', 'naziv')->toArray();

        $postrojbe = [
            ['naziv' => 'Dobrovoljno vatrogasno društvo Požeška Koprivnica', 'skraceni_naziv' => 'DVD Požeška Koprivnica', 'tip' => 'dvd', 'oib' => '29238680204', 'adresa' => 'Pož. Koprivnica 85', 'mjesto' => 'Pleternica', 'jls' => 'Pleternica', 'latitude' => 45.2544000, 'longitude' => 17.7410200],
            ['naziv' => 'Dobrovoljno vatrogasno društvo Antunovac 1927', 'skraceni_naziv' => 'DVD Antunovac 1927', 'tip' => 'dvd', 'oib' => '86257520762', 'adresa' => 'Gajska 18', 'mjesto' => 'Antunovac', 'jls' => 'Lipik', 'latitude' => 45.4969600, 'longitude' => 17.0034900],
            ['naziv' => 'Vatrogasna zajednica Požeško-slavonske županije', 'skraceni_naziv' => 'VZ PSŽ', 'tip' => 'ostalo', 'oib' => '02892060671', 'adresa' => 'Republike Hrvatske 1b', 'mjesto' => 'Požega', 'jls' => 'Požega', 'latitude' => 45.3352920, 'longitude' => 17.6772510],
            ['naziv' => 'Vatrogasna zajednica područja Požeštine', 'skraceni_naziv' => 'VZP Požeštine', 'tip' => 'ostalo', 'oib' => '01798032659', 'adresa' => 'Republike Hrvatske 1b', 'mjesto' => 'Požega', 'jls' => 'Požega', 'latitude' => 45.3324100, 'longitude' => 17.6741400],
            ['naziv' => 'Dobrovoljno vatrogasno društvo Požega', 'skraceni_naziv' => 'DVD Požega', 'tip' => 'dvd', 'oib' => '53802154741', 'adresa' => 'Industrijska 44', 'mjesto' => 'Požega', 'jls' => 'Požega', 'latitude' => 45.3374560, 'longitude' => 17.7054120],
            ['naziv' => 'Dobrovoljno vatrogasno društvo Brestovac (Pž)', 'skraceni_naziv' => 'DVD Brestovac', 'tip' => 'dvd', 'oib' => '10228943605', 'adresa' => 'Požeška 74', 'mjesto' => 'Brestovac', 'jls' => 'Brestovac', 'latitude' => 45.3308150, 'longitude' => 17.5940730],
            ['naziv' => 'Dobrovoljno vatrogasno društvo Kaptol', 'skraceni_naziv' => 'DVD Kaptol', 'tip' => 'dvd', 'oib' => '63679632048', 'adresa' => 'Trg Vilima Korajca 2', 'mjesto' => 'Kaptol', 'jls' => 'Kaptol', 'latitude' => 45.4325020, 'longitude' => 17.7232460],
            ['naziv' => 'Dobrovoljno vatrogasno društvo Kutjevo', 'skraceni_naziv' => 'DVD Kutjevo', 'tip' => 'dvd', 'oib' => '15242625064', 'adresa' => 'Republike Hrvatske 86', 'mjesto' => 'Kutjevo', 'jls' => 'Kutjevo', 'latitude' => 45.4253600, 'longitude' => 17.8836700],
            ['naziv' => 'Dobrovoljno vatrogasno društvo Čaglin', 'skraceni_naziv' => 'DVD Čaglin', 'tip' => 'dvd', 'oib' => '64859104541', 'adresa' => 'Kralja Tomislava 73', 'mjesto' => 'Čaglin', 'jls' => 'Čaglin', 'latitude' => 45.3504400, 'longitude' => 17.9885300],
            ['naziv' => 'Dobrovoljno vatrogasno društvo Jakšić', 'skraceni_naziv' => 'DVD Jakšić', 'tip' => 'dvd', 'oib' => '08401059175', 'adresa' => 'Pavla Radića 2b', 'mjesto' => 'Jakšić', 'jls' => 'Jakšić', 'latitude' => 45.3583730, 'longitude' => 17.7673070],
            ['naziv' => 'Dobrovoljno vatrogasno društvo Pleternica', 'skraceni_naziv' => 'DVD Pleternica', 'tip' => 'dvd', 'oib' => '84625592480', 'adresa' => 'Ivana Šveara 38', 'mjesto' => 'Pleternica', 'jls' => 'Pleternica', 'latitude' => 45.2838700, 'longitude' => 17.8011510],
            ['naziv' => 'Dobrovoljno vatrogasno društvo Buk', 'skraceni_naziv' => 'DVD Buk', 'tip' => 'dvd', 'oib' => '30727290397', 'adresa' => 'Buk 1', 'mjesto' => 'Buk', 'jls' => 'Pleternica', 'latitude' => 45.2974400, 'longitude' => 17.8574100],
            ['naziv' => 'Dobrovoljno vatrogasno društvo Bučje', 'skraceni_naziv' => 'DVD Bučje', 'tip' => 'dvd', 'oib' => '86436827009', 'adresa' => 'Bučje bb', 'mjesto' => 'Bučje', 'jls' => 'Pleternica', 'latitude' => 45.2319710, 'longitude' => 17.7790550],
            ['naziv' => 'Dobrovoljno vatrogasno društvo Šumanovac', 'skraceni_naziv' => 'DVD Šumanovac', 'tip' => 'dvd', 'oib' => '31002774830', 'adresa' => 'Šumanovac 37', 'mjesto' => 'Šumanovci', 'jls' => 'Kutjevo', 'latitude' => 45.3717450, 'longitude' => 17.8235580],
            ['naziv' => 'Dobrovoljno vatrogasno društvo Biškupci', 'skraceni_naziv' => 'DVD Biškupci', 'tip' => 'dvd', 'oib' => '75933519360', 'adresa' => 'Biškupci', 'mjesto' => 'Biškupci', 'jls' => 'Kutjevo', 'latitude' => 45.4399200, 'longitude' => 17.6119400],
            ['naziv' => 'Dobrovoljno vatrogasno društvo Toranj', 'skraceni_naziv' => 'DVD Toranj', 'tip' => 'dvd', 'oib' => '64380767363', 'adresa' => 'Toranj 4', 'mjesto' => 'Velika', 'jls' => 'Velika', 'latitude' => 45.4104600, 'longitude' => 17.5942300],
            ['naziv' => 'Dobrovoljno vatrogasno društvo Alilovci', 'skraceni_naziv' => 'DVD Alilovci', 'tip' => 'dvd', 'oib' => '93221661220', 'adresa' => 'Alilovci 56', 'mjesto' => 'Alilovci', 'jls' => 'Požega', 'latitude' => 45.3987140, 'longitude' => 17.7057660],
            ['naziv' => 'Dobrovoljno vatrogasno društvo Doljanovci', 'skraceni_naziv' => 'DVD Doljanovci', 'tip' => 'dvd', 'oib' => '58657730019', 'adresa' => 'Doljanovci 9', 'mjesto' => 'Doljanovci', 'jls' => 'Požega', 'latitude' => 45.4494900, 'longitude' => 17.7415300],
            ['naziv' => 'Dobrovoljno vatrogasno društvo Podgorje', 'skraceni_naziv' => 'DVD Podgorje', 'tip' => 'dvd', 'oib' => '71194960242', 'adresa' => 'Podgorje 58', 'mjesto' => 'Podgorje', 'jls' => 'Požega', 'latitude' => 45.4421800, 'longitude' => 17.7659900],
            ['naziv' => 'Dobrovoljno vatrogasno društvo Golo Brdo', 'skraceni_naziv' => 'DVD Golo Brdo', 'tip' => 'dvd', 'oib' => '81591434505', 'adresa' => 'Golo Brdo 34', 'mjesto' => 'Golo Brdo', 'jls' => 'Požega', 'latitude' => 45.4395900, 'longitude' => 17.7009300],
            ['naziv' => 'Dobrovoljno vatrogasno društvo Zakorenje', 'skraceni_naziv' => 'DVD Zakorenje', 'tip' => 'dvd', 'oib' => '61329143016', 'adresa' => 'Zakorenje 66.', 'mjesto' => 'Zakorenje', 'jls' => 'Brestovac', 'latitude' => 45.3356200, 'longitude' => 17.5510700],
            ['naziv' => 'Dobrovoljno vatrogasno društvo Ivandol', 'skraceni_naziv' => 'DVD Ivandol', 'tip' => 'dvd', 'oib' => '78975364724', 'adresa' => 'Ivandol bb', 'mjesto' => 'Ivandol', 'jls' => 'Brestovac', 'latitude' => 45.3407300, 'longitude' => 17.5071000],
            ['naziv' => 'Dobrovoljno vatrogasno društvo Jaguplije', 'skraceni_naziv' => 'DVD Jaguplije', 'tip' => 'dvd', 'oib' => '02640241290', 'adresa' => 'Jaguplije bb', 'mjesto' => 'Jaguplije', 'jls' => 'Brestovac', 'latitude' => 45.3529290, 'longitude' => 17.5914290],
            ['naziv' => 'Dobrovoljno vatrogasno društvo Orljavac', 'skraceni_naziv' => 'DVD Orljavac', 'tip' => 'dvd', 'oib' => '66969161743', 'adresa' => 'Orljavac 53', 'mjesto' => 'Orljavac', 'jls' => 'Brestovac', 'latitude' => 45.4120200, 'longitude' => 17.4957300],
            ['naziv' => 'Dobrovoljno vatrogasno društvo Vetovo', 'skraceni_naziv' => 'DVD Vetovo', 'tip' => 'dvd', 'oib' => '01726670386', 'adresa' => 'Stjepana Radića 15 b', 'mjesto' => 'Vetovo', 'jls' => 'Kutjevo', 'latitude' => 45.4133940, 'longitude' => 17.7891610],
            ['naziv' => 'Dobrovoljno vatrogasno društvo Lukač', 'skraceni_naziv' => 'DVD Lukač', 'tip' => 'dvd', 'oib' => '36799921056', 'adresa' => 'Lukač 10a', 'mjesto' => 'Lukač', 'jls' => 'Kutjevo', 'latitude' => 45.4271600, 'longitude' => 17.8108200],
            ['naziv' => 'Dobrovoljno vatrogasno društvo Djedina Rijeka', 'skraceni_naziv' => 'DVD Djedina Rijeka', 'tip' => 'dvd', 'oib' => '18034323387', 'adresa' => 'Djedina Rijeka 34', 'mjesto' => 'Djedina Rijeka', 'jls' => 'Čaglin', 'latitude' => 45.3081100, 'longitude' => 17.9485900],
            ['naziv' => 'Dobrovoljno vatrogasno društvo Ljeskovica', 'skraceni_naziv' => 'DVD Ljeskovica', 'tip' => 'dvd', 'oib' => '96754851990', 'adresa' => 'Josipa Kneževića 45 a', 'mjesto' => 'Nova Ljeskovica', 'jls' => 'Čaglin', 'latitude' => 45.3817530, 'longitude' => 18.0190590],
            ['naziv' => 'Dobrovoljno vatrogasno društvo Grabarje', 'skraceni_naziv' => 'DVD Grabarje', 'tip' => 'dvd', 'oib' => '06262544673', 'adresa' => 'Kralja Tomislava 36', 'mjesto' => 'Grabarje', 'jls' => 'Kutjevo', 'latitude' => 45.3601970, 'longitude' => 17.8571180],
            ['naziv' => 'Dobrovoljno vatrogasno društvo Bektež', 'skraceni_naziv' => 'DVD Bektež', 'tip' => 'dvd', 'oib' => '69668210480', 'adresa' => 'Bektež 4f', 'mjesto' => 'Bektež', 'jls' => 'Kutjevo', 'latitude' => 45.3965520, 'longitude' => 17.9235510],
            ['naziv' => 'Dobrovoljno vatrogasno društvo Lakušija', 'skraceni_naziv' => 'DVD Lakušija', 'tip' => 'dvd', 'oib' => '64785073771', 'adresa' => 'Lakušija 2', 'mjesto' => 'Lakušija', 'jls' => 'Pleternica', 'latitude' => 45.3419170, 'longitude' => 17.8134830],
            ['naziv' => 'Dobrovoljno vatrogasno društvo Gornji Emovci', 'skraceni_naziv' => 'DVD Gornji Emovci', 'tip' => 'dvd', 'oib' => '12900144886', 'adresa' => 'Gornji Emovci 9', 'mjesto' => 'Gornji Emovci', 'jls' => 'Požega', 'latitude' => 45.3561260, 'longitude' => 17.6416400],
            ['naziv' => 'Dobrovoljno vatrogasno društvo Sulkovci', 'skraceni_naziv' => 'DVD Sulkovci', 'tip' => 'dvd', 'oib' => '11027270296', 'adresa' => 'Sulkovci 89', 'mjesto' => 'Sulkovci', 'jls' => 'Pleternica', 'latitude' => 45.2665500, 'longitude' => 17.7750200],
            ['naziv' => 'Dobrovoljno vatrogasno društvo Knežci', 'skraceni_naziv' => 'DVD Knežci', 'tip' => 'dvd', 'oib' => '03861585099', 'adresa' => 'Knežci 36a', 'mjesto' => 'Knežci', 'jls' => 'Kutjevo', 'latitude' => 45.3392810, 'longitude' => 17.8992610],
            ['naziv' => 'Dobrovoljno vatrogasno društvo Ruševo', 'skraceni_naziv' => 'DVD Ruševo', 'tip' => 'dvd', 'oib' => '66759231681', 'adresa' => 'Ruševo  63', 'mjesto' => 'Čaglin', 'jls' => 'Čaglin', 'latitude' => 45.3095400, 'longitude' => 17.9945300],
            ['naziv' => 'Dobrovoljno vatrogasno društvo Frkljevci - Kadanovci', 'skraceni_naziv' => 'DVD Frkljevci - Kadanovci', 'tip' => 'dvd', 'oib' => '80839780676', 'adresa' => 'Frkljevci bb', 'mjesto' => 'Frkljevci', 'jls' => 'Pleternica', 'latitude' => 45.2647000, 'longitude' => 17.8176100],
            ['naziv' => 'Dobrovoljno vatrogasno društvo Zagrađe', 'skraceni_naziv' => 'DVD Zagrađe', 'tip' => 'dvd', 'oib' => '41093262332', 'adresa' => 'Zagrađe 71', 'mjesto' => 'Zagrađe', 'jls' => 'Pleternica', 'latitude' => 45.2431070, 'longitude' => 17.8083230],
            ['naziv' => 'Dobrovoljno vatrogasno društvo Trenkovo', 'skraceni_naziv' => 'DVD Trenkovo', 'tip' => 'dvd', 'oib' => '34439514623', 'adresa' => 'Trenkova  13', 'mjesto' => 'Trenkovo', 'jls' => 'Pleternica', 'latitude' => 45.4025700, 'longitude' => 17.6684000],
            ['naziv' => 'Dobrovoljno vatrogasno društvo Drenovac', 'skraceni_naziv' => 'DVD Drenovac', 'tip' => 'dvd', 'oib' => '62407247404', 'adresa' => 'Brodski Drenovac 182', 'mjesto' => 'Brodski Drenovac', 'jls' => 'Pleternica', 'latitude' => 45.2247400, 'longitude' => 17.7582200],
            ['naziv' => 'Dobrovoljno vatrogasno društvo u gospodarstvu "Kutjevo"', 'skraceni_naziv' => 'IDVD "Kutjevo"', 'tip' => 'pvpp', 'oib' => '21918659912', 'adresa' => 'Kralja Tomislava 1', 'mjesto' => 'Kutjevo', 'jls' => 'Kutjevo', 'latitude' => 45.4227330, 'longitude' => 17.8783390],
            ['naziv' => 'Vatrogasna zajednica područja Pakrac-Lipik', 'skraceni_naziv' => 'VZP Pakrac-Lipik', 'tip' => 'ostalo', 'oib' => '99234890578', 'adresa' => 'Trg bana J. Jelačića 18', 'mjesto' => 'Pakrac', 'jls' => 'Pakrac', 'latitude' => 45.4354700, 'longitude' => 17.1939000],
            ['naziv' => 'Dobrovoljno vatrogasno društvo Pakrac', 'skraceni_naziv' => 'DVD Pakrac', 'tip' => 'dvd', 'oib' => '29706592651', 'adresa' => 'Obala kralja Petra Krešimira IV 26', 'mjesto' => 'Pakrac', 'jls' => 'Pakrac', 'latitude' => 45.4427370, 'longitude' => 17.1943300],
            ['naziv' => 'Dobrovoljno vatrogasno društvo Lipik', 'skraceni_naziv' => 'DVD Lipik', 'tip' => 'dvd', 'oib' => '15720522992', 'adresa' => 'Slavonska 49', 'mjesto' => 'Lipik', 'jls' => 'Lipik', 'latitude' => 45.4115700, 'longitude' => 17.1639200],
            ['naziv' => 'Dobrovoljno vatrogasno društvo Veliki Banovac', 'skraceni_naziv' => 'DVD Veliki Banovac', 'tip' => 'dvd', 'oib' => '16285671281', 'adresa' => 'Veliki Banovac 23', 'mjesto' => 'Veliki Banovac', 'jls' => 'Pakrac', 'latitude' => 45.4902800, 'longitude' => 17.1582200],
            ['naziv' => 'Dobrovoljno vatrogasno društvo Badljevina', 'skraceni_naziv' => 'DVD Badljevina', 'tip' => 'dvd', 'oib' => '92793266619', 'adresa' => 'Kralja Tomislava 22/A', 'mjesto' => 'Badljevina', 'jls' => 'Pakrac', 'latitude' => 45.5162000, 'longitude' => 17.1875900],
            ['naziv' => 'Dobrovoljno vatrogasno društvo Omanovac', 'skraceni_naziv' => 'DVD Omanovac', 'tip' => 'dvd', 'oib' => '28030270973', 'adresa' => 'Omanovac bb', 'mjesto' => 'Omanovac', 'jls' => 'Pakrac', 'latitude' => 45.4756600, 'longitude' => 17.1859100],
            ['naziv' => 'Dobrovoljno vatrogasno društvo Donja Obrijež', 'skraceni_naziv' => 'DVD Donja Obrijež', 'tip' => 'dvd', 'oib' => '63442300452', 'adresa' => 'Donja Obrijež 65', 'mjesto' => 'Donja Obrijež', 'jls' => 'Pakrac', 'latitude' => 45.4989900, 'longitude' => 17.1358200],
            ['naziv' => 'Dobrovoljno vatrogasno društvo Prekopakra', 'skraceni_naziv' => 'DVD Prekopakra', 'tip' => 'dvd', 'oib' => '67407429288', 'adresa' => 'Ante Stačevića 30', 'mjesto' => 'Prekopakra', 'jls' => 'Pakrac', 'latitude' => 45.4370700, 'longitude' => 17.1843000],
            ['naziv' => 'Dobrovoljno vatrogasno društvo Filipovac', 'skraceni_naziv' => 'DVD Filipovac', 'tip' => 'dvd', 'oib' => '98414632577', 'adresa' => 'Tabor 12', 'mjesto' => 'Filipovac', 'jls' => 'Pakrac', 'latitude' => 45.4201500, 'longitude' => 17.1719900],
            ['naziv' => 'Dobrovoljno vatrogasno društvo Dobrovac', 'skraceni_naziv' => 'DVD Dobrovac', 'tip' => 'dvd', 'oib' => '82957159130', 'adresa' => 'Stjepana Radića 56', 'mjesto' => 'Dobrovac', 'jls' => 'Lipik', 'latitude' => 45.4260300, 'longitude' => 17.1224200],
            ['naziv' => 'Dobrovoljno vatrogasno društvo Brezine', 'skraceni_naziv' => 'DVD Brezine', 'tip' => 'dvd', 'oib' => '03843654979', 'adresa' => 'Brezine 0', 'mjesto' => 'Brezine', 'jls' => 'Lipik', 'latitude' => 45.4522440, 'longitude' => 17.0290660],
            ['naziv' => 'Dobrovoljno vatrogasno društvo Marino Selo', 'skraceni_naziv' => 'DVD Marino Selo', 'tip' => 'dvd', 'oib' => '78258500982', 'adresa' => 'Marino Selo bb', 'mjesto' => 'Marino Selo', 'jls' => 'Lipik', 'latitude' => 45.5097400, 'longitude' => 16.9743700],
            ['naziv' => 'Dobrovoljno vatrogasno društvo Brekinska', 'skraceni_naziv' => 'DVD Brekinska', 'tip' => 'dvd', 'oib' => '12781913160', 'adresa' => 'Brekinska 65', 'mjesto' => 'Brekinska', 'jls' => 'Lipik', 'latitude' => 45.4988300, 'longitude' => 17.0627100],
            ['naziv' => 'Dobrovoljno vatrogasno društvo Gradac', 'skraceni_naziv' => 'DVD Gradac', 'tip' => 'dvd', 'oib' => '24709177325', 'adresa' => 'Stjepana Radića 142', 'mjesto' => 'Gradac', 'jls' => 'Pleternica', 'latitude' => 45.3104200, 'longitude' => 17.8106190],
            ['naziv' => 'Dobrovoljno vatrogasno društvo Sesvete (pž)', 'skraceni_naziv' => 'DVD Sesvete', 'tip' => 'dvd', 'oib' => '95541676027', 'adresa' => 'Sesvete 10', 'mjesto' => 'Sesvete', 'jls' => 'Pleternica', 'latitude' => 45.3433900, 'longitude' => 17.8286300],
            ['naziv' => 'Dobrovoljno vatrogasno društvo Gaj', 'skraceni_naziv' => 'DVD Gaj', 'tip' => 'dvd', 'oib' => '38066940382', 'adresa' => 'Ulica Braće Opića 18A', 'mjesto' => 'Gaj', 'jls' => 'Lipik', 'latitude' => 45.4784220, 'longitude' => 17.0309440],
            ['naziv' => 'Javna vatrogasna postrojba Grada Požege', 'skraceni_naziv' => 'JVP Požega', 'tip' => 'jvp', 'oib' => '83816714601', 'adresa' => 'Industrijska ulica 44', 'mjesto' => 'Požega', 'jls' => 'Požega', 'latitude' => 45.3375920, 'longitude' => 17.7054330],
            ['naziv' => 'Dobrovoljno vatrogasno društvo Velika', 'skraceni_naziv' => 'DVD Velika', 'tip' => 'dvd', 'oib' => '56151082472', 'adresa' => 'Stjepana Radića 18', 'mjesto' => 'Velika', 'jls' => 'Velika', 'latitude' => 45.4540300, 'longitude' => 17.6634700],
            ['naziv' => 'Dobrovoljno vatrogasno društvo Poljana', 'skraceni_naziv' => 'DVD Poljana', 'tip' => 'dvd', 'oib' => '49120994734', 'adresa' => 'Ljudevita Gaja 41', 'mjesto' => 'Poljana', 'jls' => 'Lipik', 'latitude' => 45.4706100, 'longitude' => 16.9785000],
            ['naziv' => 'Vatrogasna zajednica Grada Požege', 'skraceni_naziv' => 'VZG Požege', 'tip' => 'ostalo', 'oib' => '90566798892', 'adresa' => 'Industrijska ulica 44', 'mjesto' => 'Požega', 'jls' => 'Požega', 'latitude' => 45.3386300, 'longitude' => 17.6857800],
            ['naziv' => 'Županijski centar 112 Požega', 'skraceni_naziv' => 'ŽC 112 Požega', 'tip' => 'ostalo', 'oib' => '99999900283', 'adresa' => 'Hrvatskih branitelja 82', 'mjesto' => 'Požega', 'jls' => 'Požega', 'latitude' => 45.3494310, 'longitude' => 17.6826880],
            ['naziv' => 'Javna vatrogasna postrojba Požeško-slavonske županije', 'skraceni_naziv' => 'JVP PSŽ', 'tip' => 'jvp', 'oib' => '35917895432', 'adresa' => 'Ivana Šveara 38', 'mjesto' => 'Pleternica', 'jls' => 'Pleternica', 'latitude' => 45.2858800, 'longitude' => 17.8018000],
            ['naziv' => 'Izvanredne dislokacije VZŽ Požeško-slavonske', 'skraceni_naziv' => 'Izv. dis. VZŽ PSŽ', 'tip' => 'ostalo', 'oib' => null, 'adresa' => null, 'mjesto' => null, 'jls' => null, 'latitude' => null, 'longitude' => null],
        ];

        foreach ($postrojbe as $p) {
            Postrojba::firstOrCreate(
                ['naziv' => $p['naziv']],
                [
                    'skraceni_naziv' => $p['skraceni_naziv'],
                    'tip' => $p['tip'],
                    'oib' => $p['oib'],
                    'adresa' => $p['adresa'],
                    'mjesto' => $p['mjesto'],
                    'jls_id' => $p['jls'] ? ($jlsMap[$p['jls']] ?? null) : null,
                    'latitude' => $p['latitude'],
                    'longitude' => $p['longitude'],
                    'aktivna' => true,
                    'operativno_spremna' => true,
                ]
            );
        }

        $this->command->info('Kreirano ' . count($postrojbe) . ' postrojbi PSŽ-a (54 DVD, 2 JVP, 1 IDVD, 6 ostalo).');
    }
}
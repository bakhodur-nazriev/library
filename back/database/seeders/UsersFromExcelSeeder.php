<?php

namespace Database\Seeders;

use App\Models\User;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;

class UsersFromExcelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @throws Exception
     */
    public function run(): void
    {
        $xlsFiles = ['studentsa.xlsx', 'studentsb.xlsx', 'studentsac.xlsx'];

        foreach ($xlsFiles as $xlsFile) {
            $xlsDoc = file(database_path('seeders/sources/' . $xlsFile));
            $spreadsheet = IOFactory::load(database_path('seeders/sources/' . $xlsFile));
            $worksheet = $spreadsheet->getActiveSheet();
            $highestRow = $worksheet->getHighestRow();

            $bar = $this->command->getOutput()->createProgressBar($highestRow);
            $bar->start();

            foreach ($xlsDoc as $row) {
                for ($row = 3; $row <= $highestRow; $row++) {
                    $bar->advance();
                    $rowData = $worksheet->rangeToArray('A' . $row . ':' . $worksheet->getHighestColumn() . $row, NULL, TRUE, FALSE);

                    if (isset($rowData[0][1])) {
                        $userNameOrigin = $rowData[0][1];
                        $userName = $userNameOrigin;

                        $userNameAndPass = $this->userEmailAndPassword($userName, $row);

                        try {
                            $user = new User();
                            $user->name = $userNameOrigin;
                            $user->email = $userNameAndPass[0];
                            $user->password = Hash::make($userNameAndPass[1]);
                            $user->save();

                        } catch (Exception $e) {
                            Log::info($e->getMessage());
                        }
                    }
                }

                $bar->finish();
                break;
            }
        }
        dd('finished');


    }


    /**
     * @throws Exception
     */
    private function userEmailAndPassword(string $initials, int $i): array
    {
        try {
            $studentsInitials = preg_replace('/\s+/', ' ', $initials);

            $names = explode(' ', $studentsInitials, 3);
            $firstTwoNames = $names[0] . ' ' . count($names)> 1 ? $names[1] : '_second_name';

            $cleanedNames = preg_replace('/[^a-zA-Zа-яА-Я\s]/u', '', $firstTwoNames);

            $latinNames = $this->customTransliterate($cleanedNames);

            $email = $i . '_' . strtolower(str_replace(' ', '_', $latinNames)) . '@mail.ru';

            $randomPassword = $this->generateRandomLatinPassword(10);

            Log::info("email: $email, pass: $randomPassword");

            return [$email, $randomPassword];
        } catch (Exception $e) {
            Log::info(['$initials' => $initials, '$names' => $names, '$e' => $e->getMessage()]);
        }

        return str_replace(' ', '_', $initials);
    }

    private function customTransliterate(string $inits): array|string|null
    {
        $transliteration = array(
            'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e',
            'ё' => 'e', 'ж' => 'zh', 'з' => 'z', 'и' => 'i', 'й' => 'y', 'к' => 'k',
            'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r',
            'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'kh', 'ц' => 'ts',
            'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch', 'ъ' => '', 'ы' => 'y', 'ь' => '',
            'э' => 'e', 'ю' => 'yu', 'я' => 'ya',
            'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E',
            'Ё' => 'E', 'Ж' => 'Zh', 'З' => 'Z', 'И' => 'I', 'Й' => 'Y', 'К' => 'K',
            'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O', 'П' => 'P', 'Р' => 'R',
            'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'Kh', 'Ц' => 'Ts',
            'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sch', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '',
            'Э' => 'E', 'Ю' => 'Yu', 'Я' => 'Ya'
        );

        $transliteratedString = strtr($inits, $transliteration);

        return preg_replace('/[^\x20-\x7E]/', '', $transliteratedString);
    }

    private function generateRandomLatinPassword(int $length): string
    {
        $latinAlphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $latinAlphabetLength = strlen($latinAlphabet);

        $randomPassword = '';
        for ($i = 0; $i < $length; $i++) {
            $randomPassword .= $latinAlphabet[random_int(0, $latinAlphabetLength - 1)];
        }

        return $randomPassword;
    }

}

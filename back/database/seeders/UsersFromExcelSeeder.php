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
        $xlsDoc = file(database_path('seeders/sources/students.xlsx'));
        $spreadsheet = IOFactory::load(database_path('seeders/sources/students.xlsx'));
        $worksheet = $spreadsheet->getActiveSheet();
        $highestRow = $worksheet->getHighestRow();
        $bar = $this->command->getOutput()->createProgressBar($highestRow);
        $bar->start();

        foreach ($xlsDoc as $row) {
            for ($row = 1; $row <= $highestRow; $row++) {
                $bar->advance();
                $rowData = $worksheet->rangeToArray('A' . $row . ':' . $worksheet->getHighestColumn() . $row, NULL, TRUE, FALSE);

                $userNameOrigin = $rowData[0][0];
                $userName = $userNameOrigin;
                $userNameAndPass = $this->userEmailAndPassword($userName);

                $user = new User();
                $user->name = $userNameOrigin;
                $user->email = $userNameAndPass[0];
                $user->password = Hash::make($userNameAndPass[1]);
                $user->save();
            }

            $bar->finish();
            dd('closed');
        }
    }


    /**
     * @throws Exception
     */
    private function userEmailAndPassword(string $initials): array
    {
        $studentsInitials = preg_replace('/\s+/', ' ', $initials);

        $names = explode(' ', $studentsInitials, 3);
        $firstTwoNames = $names[0] . ' ' . $names[1];

        $cleanedNames = preg_replace('/[^a-zA-Zа-яА-Я\s]/u', '', $firstTwoNames);

        $latinNames = $this->customTransliterate($cleanedNames);

        $email = strtolower(str_replace(' ', '_', $latinNames)) . '@mail.ru';

        $randomPassword = $this->generateRandomLatinPassword(10);

        Log::info("email: $email, pass: $randomPassword");

        return [$email, $randomPassword];
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
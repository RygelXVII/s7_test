<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class Test extends Command
{
    protected $signature = 'test';
    protected $description = 'Test';

    protected $fields = [
        'fio',
        'email',
        'phone',
        'birthday',
        'address',
        'organization',
        'post',
        'type_employment',
        'date_admission',
    ];

    public function handle()
    {
        $path = __DIR__.'/import.csv';

        $updateTime = Carbon::now();

        $countInsert = 0;
        $countUpdate = 0;
        $countDelete = 0;

        if (($handle = fopen($path, "r")) !== false) {
            //скипаем первую строку
            fgetcsv($handle);
            while (($data = fgetcsv($handle, 1000, ";")) !== false) {
                $num = count($data);
                $newData = ['update_at' => $updateTime];
                for ($c = 0; $c < $num; $c++) {
                    $newData[$this->fields[$c]] = iconv('CP1251', 'UTF-8', $data[$c]);
                }
                
                $user = DB::table('users')
                    ->where('email', $newData['email'])
                    ->first(['id']);

                if ($user) {
                    $this->update($user->id, $newData);
                    $countUpdate++;
                } else {
                    $this->create($newData);
                    $countInsert++;
                }
            }
            fclose($handle);

            $countDelete = $this->delete($updateTime);
        }

        echo "Добавленно $countInsert, Обновленно $countUpdate, Удаленно $countDelete ";
    }

    private function create(array $insert)
    {
        DB::table('users')
            ->insert($insert);
    }

    private function update(int $id, array $insert)
    {
        DB::table('users')
            ->where('id', $id)
            ->update($insert);
    }

    private function delete(string $updateTime): int
    {
        return DB::table('users')
            ->where('update_at', '<', $updateTime)
            ->delete();
    }
}

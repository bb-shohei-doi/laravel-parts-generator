<?php

namespace Sd883\LaravelPartsGenerator\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class CreateMasterMaintenance extends Command
{
    protected $rootDir;

    /**
     * 前提情報
     * 1. {table}モデルは既に存在する
     * 2. {table}sテーブルは既に存在する
     * 3. {table}sテーブルには、commentが設定されている
     */

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fw:create-master-maintenance {table}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create view content';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->rootDir = str_replace('Console/Commands', '', __DIR__);

        // 1. テーブル名（単数形）を引数で受け取る
        $table = ucfirst($this->argument('table'));
        // 2. web.phpにルーティングを追加get('/{table}', '{table}Controller@index')の形式
        $this->createRoute($table);
        // 3. {table}Controllerを作成
        $this->createController($table);
        // 4. {table}Controllerのpublic function indexにて、view('{table}.index', [{table} => {table}::all()])を返す
        $this->updateControllerIndexMethod($table);
        // 5. resources/viewsに{table}ディレクトリを作成
        $this->createViewDirectory($table);
        // 6. resources/views/{table}にindex.blade.phpを作成
        $this->createIndexBlade($table);
        // 7-1. resources/views/livewire/{table}ディレクトリを作成
        $this->createViewLivewireDirectory($table);
        // 7-2. app/Livewire/{table}ディレクトリを作成
        $this->createLivewireDirectory($table);
        // 8. Templateを利用して、list部品を作成
        $this->createLivewireListComponent($table);
        // 9. Templateを利用して、create部品を作成
        $this->createLivewireCreateModalComponent($table);
        // 10. Templateを利用して、update部品を作成
        $this->createLivewireUpdateModalComponent($table);
        // 11. Templateを利用して、delete部品を作成
        $this->createLivewireDeleteModalComponent($table);
        // 12. list.jsを作成
        $this->createListJs($table);
        // 13. master-maintenance-base.blade.phpを作成
        $this->createMasterMaintenanceBase($table);
        // 14. master-maintenance.cssをコピー
        $this->copyMasterMaintenanceCss();
    }

    private function createRoute($table)
    {
        $route = "Route::get('/{$table}', [{$table}Controller::class, 'index']);";
        $path = base_path('routes/web.php');
        $content = file_get_contents($path);
        $content .= "\n" . $route;
        $content = str_replace(
            'use Illuminate\Support\Facades\Route;',
            "use Illuminate\Support\Facades\Route;\n" .
                "use App\Http\Controllers\\{$table}Controller;",
            $content
        );
        file_put_contents($path, $content);
    }

    private function createController($table)
    {
        Artisan::call('make:controller', [
            'name' => $table . 'Controller',
            '--model' => $table,
            '--resource' => true,
        ]);
    }

    /**
     * public function index
     * {
     *     //
     * }
     * を以下のように置換する
     * public function index
     * {
     *     return view('{table}.index', ['{table}' => {table}::all()]);
     * }
     *
     * @param string $table
     * @return void
     */
    private function updateControllerIndexMethod(string $table)
    {
        $path = app_path('Http/Controllers/' . $table . 'Controller.php');
        $content = file_get_contents($path);

        $content = preg_replace(
            '/public function index\(\)\n    {\n        \/\//',
            "public function index()\n    {\n        return view('{$table}.index', ['{$table}' => {$table}::all()]);",
            $content
        );

        file_put_contents($path, $content);
    }

    private function createViewDirectory($table)
    {
        $path = resource_path('views/' . $table);
        if (!file_exists($path)) {
            mkdir($path);
        }
    }

    private function createIndexBlade($table)
    {
        $lcTable = strtolower($table);
        $ucTable = ucfirst($table);
        // resources/views/templates/index.blade.phpの内容をコピーして
        // :tableを$tableに置換してresources/views/{table}/index.blade.phpに保存する
        $template = file_get_contents($this->rootDir . 'resources/views/templates/index.blade.php');
        $content = str_replace(':lc:table', $lcTable, $template);
        $content = str_replace(':uc:table', $ucTable, $content);
        file_put_contents(resource_path('views/' . $table . '/index.blade.php'), $content);
    }

    private function createViewLivewireDirectory(string $table)
    {
        if (!file_exists(resource_path('views/livewire'))) {
            mkdir(resource_path('views/livewire'));
        }
        $path = resource_path('views/livewire/' . ucfirst($table));
        if (!file_exists($path)) {
            mkdir($path);
        }
    }

    private function createLivewireDirectory(string $table)
    {
        if (!file_exists(app_path('Livewire'))) {
            mkdir(app_path('Livewire'));
        }
        $path = app_path('Livewire/' . ucfirst($table));
        if (!file_exists($path)) {
            mkdir($path);
        }
    }

    /**
     * resources/views/livewire/template/list-template.phpをコピーして
     * resources/views/livewire/{table}/list-{table}.blade.phpに保存する
     * app/Livewire/Template/ListTemplate.phpをコピーして
     * app/Livewire/{table}/List{table}.phpに保存する
     * ただし、:uc:table, :lc:tableを$tableに置換する
     * また、:columns, :copyPropertiesを適切な値に置換する
     *
     * @param string $table
     * @return void
     */
    private function createLivewireListComponent(string $table)
    {
        $ucTable = ucfirst($table);
        $lcTable = strtolower($table);
        $template = file_get_contents($this->rootDir . 'resources/views/livewire/template/list-template.blade.php');
        $content = str_replace(':uc:table', $ucTable, $template);
        $content = str_replace(':lc:table', $lcTable, $content);
        file_put_contents(resource_path('views/livewire/' . $ucTable . '/list-' . $lcTable . '.blade.php'), $content);

        $template = file_get_contents($this->rootDir . 'Livewire/Template/ListTemplate.php');
        $content = str_replace(':uc:table', $ucTable, $template);
        $content = str_replace(':lc:table', $lcTable, $content);
        file_put_contents(app_path('Livewire/' . $ucTable . '/List' . $ucTable . '.php'), $content);
    }

    /**
     * resources/views/livewire/template/create-template-modal.phpをコピーして
     * resources/views/livewire/{table}/create-{table}-modal.blade.phpに保存する
     * app/Livewire/Template/CreateTemplateModal.phpをコピーして
     * app/Livewire/{table}/Create{table}Modal.phpに保存する
     * ただし、:uc:table, :lc:tableを$tableに置換する
     * また、:columns, :copyPropertiesを適切な値に置換する
     *
     * @param string $table
     * @return void
     */
    private function createLivewireCreateModalComponent(string $table)
    {
        $ucTable = ucfirst($table);
        $lcTable = strtolower($table);
        $template = file_get_contents($this->rootDir . 'resources/views/livewire/template/create-template-modal.blade.php');
        $content = str_replace(':uc:table', $ucTable, $template);
        $content = str_replace(':lc:table', $lcTable, $content);
        $content = str_replace('    :columns', $this->getColumnInputs($table), $content);
        file_put_contents(resource_path('views/livewire/' . $ucTable . '/create-' . $lcTable . '-modal.blade.php'), $content);

        $template = file_get_contents($this->rootDir . 'Livewire/Template/CreateTemplateModal.php');
        $content = str_replace(':uc:table', $ucTable, $template);
        $content = str_replace(':lc:table', $lcTable, $content);
        $content = str_replace(':columns', $this->getColumns($table), $content);
        file_put_contents(app_path('Livewire/' . $ucTable . '/Create' . $ucTable . 'Modal.php'), $content);
    }

    /**
     * resources/views/livewire/template/update-template-modal.phpをコピーして
     * resources/views/livewire/{table}/update-{table}-modal.blade.phpに保存する
     * app/Livewire/Template/UpdateTemplateModal.phpをコピーして
     * app/Livewire/{table}/Update{table}Modal.phpに保存する
     * ただし、:uc:table, :lc:tableを$tableに置換する
     * また、:columns, :copyPropertiesを適切な値に置換する
     *
     * @param string $table
     * @return void
     */
    private function createLivewireUpdateModalComponent(string $table)
    {
        $ucTable = ucfirst($table);
        $lcTable = strtolower($table);
        $template = file_get_contents($this->rootDir . 'resources/views/livewire/template/update-template-modal.blade.php');
        $content = str_replace(':uc:table', $ucTable, $template);
        $content = str_replace(':lc:table', $lcTable, $content);
        $content = str_replace('    :columns', $this->getColumnInputs($table), $content);
        file_put_contents(resource_path('views/livewire/' . $ucTable . '/update-' . $lcTable . '-modal.blade.php'), $content);

        $template = file_get_contents($this->rootDir . 'Livewire/Template/UpdateTemplateModal.php');
        $content = str_replace(':uc:table', $ucTable, $template);
        $content = str_replace(':lc:table', $lcTable, $content);
        $content = str_replace(':columns', $this->getColumns($table), $content);
        $content = str_replace(':copyProperties', $this->getCopyProperties($table), $content);
        file_put_contents(app_path('Livewire/' . $ucTable . '/Update' . $ucTable . 'Modal.php'), $content);
    }

    /**
     * resources/views/livewire/template/delete-template-modal.phpをコピーして
     * resources/views/livewire/{table}/delete-{table}-modal.blade.phpに保存する
     * app/Livewire/Template/DeleteTemplateModal.phpをコピーして
     * app/Livewire/{table}/Delete{table}Modal.phpに保存する
     * ただし、:uc:table, :lc:tableを$tableに置換する
     * また、:columns, :copyPropertiesを適切な値に置換する
     *
     * @param string $table
     * @return void
     */
    private function createLivewireDeleteModalComponent(string $table)
    {
        $ucTable = ucfirst($table);
        $lcTable = strtolower($table);
        $template = file_get_contents($this->rootDir . 'resources/views/livewire/template/delete-template-modal.blade.php');
        $content = str_replace(':uc:table', $ucTable, $template);
        $content = str_replace(':lc:table', $lcTable, $content);
        file_put_contents(resource_path('views/livewire/' . $ucTable . '/delete-' . $lcTable . '-modal.blade.php'), $content);

        $template = file_get_contents($this->rootDir . 'Livewire/Template/DeleteTemplateModal.php');
        $content = str_replace(':uc:table', $ucTable, $template);
        $content = str_replace(':lc:table', $lcTable, $content);
        file_put_contents(app_path('Livewire/' . $ucTable . '/Delete' . $ucTable . 'Modal.php'), $content);
    }

    /**
     * Livewireのコンポーネントに必要なカラムを取得する
     *
     * @param string $table
     * @return string
     */
    private function getColumns(string $table): string
    {
        $colInfos = $this->getColumnInformation($table);

        $columns = '';
        $model = app('App\Models\\' . $table);
        foreach ($model->getFillable() as $column) {
            $rules = $this->getValidateRules($colInfos, $column);
            $columns .= "    #[Validate('{$rules}')]\n";
            $columns .= "    public \${$column};\n";
        }
        return $columns;
    }

    public function getValidateRules(array $colInfos, string $column): string
    {
        $validate = [];
        // 各カラムのバリデーションを設定する
        if ($colInfos[$column]['nullable'] === false) {
            $validate[] = 'required';
        }
        if ($colInfos[$column]['type'] === 'varchar') {
            $validate[] = 'max:' . $colInfos[$column]['length'];
        }
        if ($colInfos[$column]['type'] === 'int') {
            $validate[] = 'integer';
        }
        if ($colInfos[$column]['type'] === 'decimal') {
            $validate[] = 'numeric';
        }
        return implode('|', $validate);
    }

    /**
     * Livewireのコンポーネントに必要なカラムの値を移送する
     *
     * @param string $table
     * @return string
     */
    private function getCopyProperties(string $table): string
    {
        $lcTable = strtolower($table);
        $copyProperties = '';
        $model = app('App\Models\\' . $table);
        foreach ($model->getFillable() as $column) {
            $copyProperties .= "            \$this->{$column} = \${$lcTable}->{$column};\n";
        }
        return $copyProperties;
    }

    /**
     * resources/js/template/list.jsをコピーして
     * resources/js/{table}/list.jsに保存する
     * ただし、:tableを$tableに置換する
     *
     * @param string $table
     * @return void
     */
    private function createListJs(string $table)
    {
        $ucTable = ucfirst($table);
        $lcTable = strtolower($table);
        $template = file_get_contents($this->rootDir . 'resources/js/template/list.js');
        $content = str_replace(':uc:table', $ucTable, $template);
        $content = str_replace(':lc:table', $lcTable, $content);
        $content = str_replace(':columns', $this->getBindings($table), $content);

        $path = resource_path('js/' . $lcTable);
        if (!file_exists($path)) {
            mkdir($path);
        }

        file_put_contents(resource_path('js/' . $lcTable . '/list.js'), $content);
    }

    /**
     * $tableのカラムを取得し
     * FlexGridのcolumnsにバインディングする文字列を返す
     *
     * @param string $table
     * @return string
     */
    private function getBindings(string $table): string
    {
        $colInfos = $this->getColumnInformation($table);

        $model = app('App\Models\\' . $table);
        $columns = '';
        foreach ($model->getFillable() as $column) {
            $align = 'left';
            if (array_key_exists($column, $colInfos)) {
                $comment = $colInfos[$column]['comment'];
                if ($colInfos[$column]['type'] === 'varchar') {
                    $align = 'left';
                } else {
                    $align = 'right';
                }
            } else {
                $comment = $column;
            }
            $columns .= "        { header: '{$comment}', binding: '{$column}', width: '*', isReadOnly: true, align: '{$align}' },\n";
        }
        return $columns;
    }

    private function getColumnInputs(string $table): string
    {
        $colInfo = $this->getColumnInformation($table);

        $model = app('App\Models\\' . $table);
        $columns = '';
        foreach ($model->getFillable() as $column) {
            if (array_key_exists($column, $colInfo)) {
                $comment = $colInfo[$column]['comment'];
            } else {
                $comment = $column;
            }
            \Log::debug($comment . ':' . $column);
            $columns .= "        <div class=\"form-group\">\n";
            $columns .= "            <label for=\"{$column}\" class=\"block text-sm font-medium text-gray-700\">{$comment}</label>\n";
            $columns .= "            <input type=\"text\" class=\"form-control border border-gray-300 rounded-md\" id=\"{$column}\" wire:model=\"{$column}\">\n";
            $columns .= "        </div>\n";
        }
        return $columns;
    }

    private function getColumnInformation(string $table): array
    {
        $descriptions = \DB::table('information_schema.columns')
            ->select(
                'column_name',
                'column_comment',
                'is_nullable',
                'data_type',
                'character_maximum_length',
                'numeric_precision',
                'numeric_scale'
            )
            ->where('table_name', strtolower($table) . 's')
            ->get();

        $model = app('App\Models\\' . $table);
        $columns = [];
        foreach ($model->getFillable() as $column) {
            $colInfo = $descriptions->where('COLUMN_NAME', $column)->first();
            $columns[$column] = [
                'name'      => $column,
                'comment'   => $colInfo->COLUMN_COMMENT,
                'nullable'  => $colInfo->IS_NULLABLE === 'YES',
                'type'      => $colInfo->DATA_TYPE,
                'length'    => $colInfo->CHARACTER_MAXIMUM_LENGTH,
                'precision' => $colInfo->NUMERIC_PRECISION,
                'scale'     => $colInfo->NUMERIC_SCALE,
            ];
        }
        return $columns;
    }

    private function createMasterMaintenanceBase(): void
    {
        // if not exists, create resources/views/layouts
        $path = resource_path('views/layouts');
        if (!file_exists($path)) {
            mkdir($path);
        }
        // copy resources/views/templates/master-maintenance-base.blade.php to resources/views/layouts/master-maintenance-base.blade.php
        $template = file_get_contents($this->rootDir . 'resources/views/templates/master-maintenance-base.blade.php');
        file_put_contents(resource_path('views/layouts/master-maintenance-base.blade.php'), $template);
    }

    private function copyMasterMaintenanceCss(): void
    {
        $css = file_get_contents($this->rootDir . 'resources/css/master-maintenance.css');
        file_put_contents(resource_path('css/master-maintenance.css'), $css);
    }
}

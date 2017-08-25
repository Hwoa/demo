<?php

return [
    'exclusion_error'           =>  ':package_numberは排他処理により出力できませんでした。',
    'exclusion_single_error'    =>  '排他処理により保存できませんでした。',
    'exclusion_multi_error'     =>  ':attributeは排他処理により保存できませんでした。',
    'amount_error'              =>  ':package_numberは出力枚数が「０」になっています。',
    'path_error'                =>  ':nameの:attribute、「:value」は存在しません。',
    'writable_error'            =>  ':attributeに書き込み権限がありません。',
    'write_error'               =>  ':nameを:valueに書き込むことができませんでした。',
    'delete_error'              =>  '既に存在する:nameを:valueから削除することができませんでした。',
    'exists_error'              =>  ':nameに:valueが既に存在するため操作を続行できません。',
    'dir_error'                 =>  ':valueフォルダを作成することができませんでした。',
    'copy_error'                =>  '「:value1」から「:value2」にファイルをコピーできませんでした。',
    'wait_error'                =>  ':nameは:value秒を経過してジョブが終了しませんでした。',
    'no_file_error'             =>  ':attributeにファイルが存在しません。',
    'default'                   =>  ':attribute',
    'file_lock'                 =>  ':attributeのファイルロックに失敗しました。',
    'import_no_data'            =>  '取り込み可能なデータが存在しませんでした。',
    'enabled_error'             =>  'プリンター( :attribute )が無効です。',

    'attribute' => [
        'from_path' => '出力元フォルダ',
        'to_path'   => '出力先フォルダ',
    ],
];

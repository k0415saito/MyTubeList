<?php

return [

  /*
  |--------------------------------------------------------------------------
  | Validation Language Lines
  |--------------------------------------------------------------------------
  |
  | The following language lines contain the default error messages used by
  | the validator class. Some of these rules have multiple versions such
  | as the size rules. Feel free to tweak each of these messages here.
  |
  */

  'accepted'             => ':attribute が未承認です',
  'active_url'           => ':attribute は有効なURLではありません',
  'after'                => ':attribute は :date より後の日付にしてください',
  'after_or_equal'       => ':attribute は :date 以降の日付にしてください',
  'alpha'                => ':attribute は英字のみ有効です',
  'alpha_dash'           => ':attribute は「英字」「数字」「-(ダッシュ)」「_(下線)」のみ有効です',
  'alpha_num'            => ':attribute は「英字」「数字」のみ有効です',
  'array'                => ':attribute は配列タイプのみ有効です',
  'before'               => ':attribute は :date より前の日付にしてください',
  'before_or_equal'      => ':attribute は :date 以前の日付にしてください',
  'between'              => [
    'numeric' => ':attribute は :min ～ :max までの数値まで有効です',
    'file'    => ':attribute は :min ～ :max キロバイトまで有効です',
    'string'  => ':attribute は :min ～ :max 文字まで有効です',
    'array'   => ':attribute は :min ～ :max 個まで有効です',
  ],
  'boolean'              => ':attributeの値は true もしくは false のみ有効です',
  'confirmed'            => '確認用の:attributeと一致しません',
  'date'                 => ':attributeを有効な日付形式にしてください',
  'date_format'          => ':attributeを :format 書式と一致させてください',
  'different'            => ':attributeを :other と違うものにしてください',
  'digits'               => ':attributeは :digits 桁のみ有効です',
  'digits_between'       => ':attributeは :min ～ :max 桁のみ有効です',
  'dimensions'           => ':attributeルールに合致する画像サイズのみ有効です',
  'distinct'             => ':attributeに重複している値があります',
  'email'                => ':attributeの書式が正しくありません',
  'exists'               => ':attribute 無効な値です',
  'file'                 => ':attribute アップロード出来ないファイルです',
  'filled'               => ':attribute 値を入力してください',
  'image'                => ':attribute 画像は「jpg」「png」「bmp」「gif」「svg」のみ有効です',
  'in'                   => ':attribute 無効な値です',
  'in_array'             => ':attributeは:otherと一致する必要があります',
  'integer'              => ':attributeは整数のみ有効です',
  'ip'                   => ':attribute IPアドレスの書式のみ有効です',
  'ipv4'                 => ':attribute IPアドレス(IPv4)の書式のみ有効です',
  'ipv6'                 => ':attribute IPアドレス(IPv6)の書式のみ有効です',
  'json'                 => ':attribute 正しいJSON文字列のみ有効です',
  'max'                  => [
    'numeric' => ':attributeは:max 以下のみ有効です',
    'file'    => ':attributeは:max KB以下のファイルのみ有効です',
    'string'  => ':attributeは:max 文字以下のみ有効です',
    'array'   => ':attributeは:max 個以下のみ有効です',
  ],
  'mimes'                => ':attributeは :values タイプのみ有効です',
  'mimetypes'            => ':attributeは :values タイプのみ有効です',
  'min'                  => [
    'numeric' => ':attributeは:min以上のみ有効です',
    'file'    => ':attributeは:minKB以上のファイルのみ有効です',
    'string'  => ':attributeは:min文字以上のみ有効です',
    'array'   => ':attributeは:min個以上のみ有効です',
  ],
  'not_in'               => ':attributeは無効な値です',
  'numeric'              => ':attributeは数字のみ有効です',
  'present'              => ':attributeが存在しません',
  'regex'                => ':attributeが無効な値です',
  'required'             => ':attributeは必須です',
  'required_if'          => ':attributeは:other が :value には必須です',
  'required_unless'      => ':attributeは:other が :values でなければ必須です',
  'required_with'        => ':attributeは:values が入力されている場合は必須です',
  'required_with_all'    => ':attributeは:values が入力されている場合は必須です',
  'required_without'     => ':attributeは:values が入力されていない場合は必須です',
  'required_without_all' => ':attributeは:values が入力されていない場合は必須です',
  'same'                 => ':attributeは:other と同じ場合のみ有効です',
  'size'                 => [
    'numeric' => ':attribute は :size のみ有効です',
    'file'    => ':attribute は :size KBのみ有効です',
    'string'  => ':attribute は :size 文字のみ有効です',
    'array'   => ':attribute は :size 個のみ有効です',
  ],
  'string'               => ':attributeは文字列のみ有効です',
  'timezone'             => ':attribute正しいタイムゾーンのみ有効です',
  'unique'               => ':attributeは既に存在します',
  'uploaded'             => ':attributeアップロードに失敗しました',
  'url'                  => ':attributeは正しいURL書式のみ有効です',

  /*
  |--------------------------------------------------------------------------
  | Custom Validation Language Lines
  |--------------------------------------------------------------------------
  |
  | Here you may specify custom validation messages for attributes using the
  | convention "attribute.rule" to name the lines. This makes it quick to
  | specify a specific custom language line for a given attribute rule.
  |
  */

  'custom' => [
    'attribute-name' => [
      'rule-name' => 'custom-message',
    ],
  ],

  /*
  |--------------------------------------------------------------------------
  | Custom Validation Attributes
  |--------------------------------------------------------------------------
  |
  | The following language lines are used to swap attribute place-holders
  | with something more reader friendly such as E-Mail Address instead
  | of "email". This simply helps us make messages a little cleaner.
  |
  */

  'attributes' => [
      'email' => 'メールアドレス', 
      'password' => 'パスワード'
    ],

];
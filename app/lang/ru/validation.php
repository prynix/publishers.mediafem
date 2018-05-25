<?php

return array(

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

	"accepted"             => ":attribute должено быть принято.",
	"active_url"           => ":attribute не является действительным URL.",
	"after"                => ":attribute должен быть числом после :date.",
	"alpha"                => ":attribute может содержать только буквы.",
	"alpha_dash"           => ":attribute может содержать только буквы, цифры, и черточки.",
	"alpha_num"            => ":attribute может содержать только буквы и цифры.",
	"array"                => ":attribute должен быть массивом.",
	"before"               => ":attribute должен быть числом до :date.",
	"between"              => array(
		"numeric" => ":attribute должен быть между :min и :max.",
		"file"    => ":attribute должен иметь между :min и :max kilobytes.",
		"string"  => ":attribute должен иметь между :min и :max букв.",
		"array"   => ":attribute должен иметь между :min и :max элементов.",
	),
	"confirmed"            => ":attribute подтверждения не соответствует.",
	"date"                 => ":attribute не является правильным чслом.",
	"date_format"          => ":attribute не соответствует формату :format.",
	"different"            => ":attribute и :other должны быть разными.",
	"digits"               => ":attribute должно быть :digits цифры.",
	"digits_between"       => ":attribute должно быть между :min и :max цифр.",
	"email"                => ":attribute должен быть действительный адрес электронной почты.",
	"exists"               => "Выбранный :attribute недействителен.",
	"image"                => ":attribute должно быть изображение.",
	"in"                   => "Выбранный :attribute недействителен.",
	"integer"              => ":attribute должно быть целым числом.",
	"ip"                   => ":attribute должен быть действительный IP-адрес.",
	"max"                  => array(
		"numeric" => ":attribute не может быть больше, чем :max.",
		"file"    => ":attribute не может быть больше, чем :max kilobytes.",
		"string"  => ":attribute не может иметь больше, чем :max букв.",
		"array"   => ":attribute не может иметь больше, чем :max элементов.",
	),
	"mimes"                => ":attribute должен быть файл типа: :values.",
	"min"                  => array(
		"numeric" => ":attribute должны быть как минимум :min.",
		"file"    => ":attribute должны быть как минимум :min kilobytes.",
		"string"  => ":attribute должны иметь как минимум :min букв.",
		"array"   => ":attribute должны иметь как минимум :min элементов.",
	),
	"not_in"               => "Выбранный :attribute недействителен.",
	"numeric"              => ":attribute должны быть цифрой.",
	"regex"                => ":attribute формат недопустимый.",
	"required"             => ":attribute поле является обязательным.",
	"required_if"          => ":attribute поле является обязательным когда :other является :value.",
	"required_with"        => ":attribute поле является обязательным когда :values присутствует.",
	"required_with_all"    => ":attribute поле является обязательным когда :values присутствует.",
	"required_without"     => ":attribute поле является обязательным когда :values не присутствует.",
	"required_without_all" => ":attribute поле является обязательным когда ни один из :values присутствует.",
	"same"                 => ":attribute и :other должны совпадать.",
	"size"                 => array(
		"numeric" => ":attribute должен быть :size.",
		"file"    => ":attribute должен иметь :size kilobytes.",
		"string"  => ":attribute должен иметь :size букв.",
		"array"   => ":attribute должен иметь :size элементов.",
	),
        "equal"                => ":attribute должен быть равен :value",
        "greater"               => "Сумма должна быть больше, чем US$ :value",
        "lower"               => "Сумма должна быть меньше, чем US$ :value",
	"unique"               => ":attribute уже был принят.",
	"url"                  => ":attribute формат недопустимый.",
        "required_url"             => "URL ':value' необходима.",
        "itHasBeMoreThanOne" => "Необходимо иметь более одного домена",
        "curl_url"             => "Данный URL является недействительным.",
        "curl_specific_url"    => "URL ':value' является недействительной.",
    
        "recaptcha" => 'Поле :attribute не является правильным.',

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

	'custom' => array(
		'attribute-name' => array(
			'rule-name' => 'custom-message',
		),
	),

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

	'attributes' => array(),

);

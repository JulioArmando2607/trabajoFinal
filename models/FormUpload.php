<?php

namespace app\models;

use yii\base\Model;

class FormUpload extends Model {

    public $file;

    public function rules() {
        return [
            ['file', 'file',
                'skipOnEmpty' => false,
                'uploadRequired' => 'No has seleccionado ningún archivo', //Error
                'maxSize' => 1024 * 1024 * 10, //1 MB
                'tooBig' => 'El tamaño máximo permitido es 1MB', //Error
//                'minSize' => 10, //10 Bytes
                'tooSmall' => 'El tamaño mínimo permitido son 10 BYTES', //Error
//                'extensions' => 'jpg,pdf,png,doc,docx,xls,xlsx',
                'wrongExtension' => 'El archivo {file} no contiene una extensión permitida {extensions}', //Error
                'maxFiles' => 12,
                'tooMany' => 'El máximo de archivos permitidos son {limit}', //Error
            ],
        ];
    }

    public function attributeLabels() {
        return [
            'file' => 'Seleccionar archivo:',
        ];
    }

}

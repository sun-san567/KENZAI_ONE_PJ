<?php

namespace App\Helpers;

class FileHelper
{
    /**
     * ファイルがプレビュー可能かどうかを判定する
     *
     * @param string $mimeType MIMEタイプ
     * @return bool
     */
    public static function isPreviewable($mimeType)
    {
        $previewableTypes = [
            'image/',
            'application/pdf',
            'text/',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/msword',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ];

        foreach ($previewableTypes as $type) {
            if (str_starts_with($mimeType, $type)) {
                return true;
            }
        }

        return false;
    }

    /**
     * MIMEタイプに基づいてファイルアイコンのクラスを取得
     *
     * @param string $mimeType MIMEタイプ
     * @return string
     */
    public static function getFileIconClass($mimeType)
    {
        $icons = [
            'application/pdf' => 'fa-file-pdf text-red-500',
            'image/' => 'fa-file-image text-blue-500',
            'application/vnd.ms-excel' => 'fa-file-excel text-green-500',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'fa-file-excel text-green-500',
            'application/msword' => 'fa-file-word text-blue-700',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'fa-file-word text-blue-700',
            'text/' => 'fa-file-alt text-gray-700',
        ];

        foreach ($icons as $type => $icon) {
            if (str_starts_with($mimeType, $type)) {
                return $icon;
            }
        }

        return 'fa-file text-gray-500';
    }
}

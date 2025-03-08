<?php

use App\Helpers\FileHelper;

if (!function_exists('isPreviewable')) {
    /**
     * ファイルがプレビュー可能かどうかを判定する
     *
     * @param string $mimeType MIMEタイプ
     * @return bool
     */
    function isPreviewable($mimeType)
    {
        return FileHelper::isPreviewable($mimeType);
    }
}

if (!function_exists('getFileIconClass')) {
    /**
     * MIMEタイプに基づいてファイルアイコンのクラスを取得
     *
     * @param string $mimeType MIMEタイプ
     * @return string
     */
    function getFileIconClass($mimeType)
    {
        return FileHelper::getFileIconClass($mimeType);
    }
}

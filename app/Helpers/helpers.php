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
        return strpos($mimeType, 'image/') === 0
            || $mimeType === 'application/pdf'
            || $mimeType === 'text/plain';
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
        if (strpos($mimeType, 'image/') === 0) {
            return 'fa-file-image text-green-500';
        } elseif (strpos($mimeType, 'video/') === 0) {
            return 'fa-file-video text-purple-500';
        } elseif (strpos($mimeType, 'audio/') === 0) {
            return 'fa-file-audio text-yellow-500';
        } elseif ($mimeType === 'application/pdf') {
            return 'fa-file-pdf text-red-500';
        } elseif (in_array($mimeType, [
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ])) {
            return 'fa-file-word text-blue-600';
        } elseif (in_array($mimeType, [
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ])) {
            return 'fa-file-excel text-green-600';
        } elseif (in_array($mimeType, [
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation'
        ])) {
            return 'fa-file-powerpoint text-orange-500';
        } elseif (in_array($mimeType, [
            'application/zip',
            'application/x-rar-compressed',
            'application/x-tar',
            'application/gzip'
        ])) {
            return 'fa-file-archive text-gray-500';
        } elseif ($mimeType === 'text/plain') {
            return 'fa-file-alt text-gray-600';
        } else {
            return 'fa-file text-gray-400';
        }
    }
}

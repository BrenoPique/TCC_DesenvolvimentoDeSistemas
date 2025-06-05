<?php
require_once __DIR__ . '/../utils/Popup.php';

class ImageUploader
{
    private static $maxSize = 1048576; // 1 MB em bytes
    private static $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];

    private static function validateUpload($file)
    {
        if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("Nenhuma imagem enviada.");
        }

        if (!self::isValidImage($file['tmp_name'])) {
            throw new Exception("O arquivo enviado não é uma imagem válida.");
        }

        if (!self::isAllowedType($file['type'])) {
            throw new Exception("Tipo de imagem não permitido. Use JPG ou PNG.");
        }

        if (!self::isAllowedSize($file['size'])) {
            throw new Exception("A imagem deve ter no máximo 1MB.");
        }
    }

    private static function ensureDirectoryExists($path)
    {
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
    }

    /**
     * Realiza o upload da imagem do prato
     */
    public static function saveDishImage($dishId, $partnerId, $file)
    {
        try {
            self::validateUpload($file);

            $uploadDir = __DIR__ . "/../uploads/partner/{$partnerId}/";
            self::ensureDirectoryExists($uploadDir);

            $targetFileName = $dishId . ".jpg";
            $targetFilePath = $uploadDir . $targetFileName;

            if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
                return "uploads/partner/{$partnerId}/{$targetFileName}";
            }
            throw new Exception("Erro ao mover a imagem para o diretório.");
        } catch (Exception $e) {
            Popup::showError($e->getMessage());
            return false;
        }
    }

    /**
     * Realiza o upload da logo do parceiro
     */
    public static function savePartnerLogo($partnerId, $file)
    {
        try {
            self::validateUpload($file);

            $partnerDir = __DIR__ . '/../uploads/partner/' . $partnerId;
            self::ensureDirectoryExists($partnerDir);

            $targetFile = $partnerDir . '/logo.jpg';
            $uploadPath = '/uploads/partner/' . $partnerId . '/logo.jpg';

            if (move_uploaded_file($file['tmp_name'], $targetFile)) {
                return $uploadPath;
            }
            throw new Exception('Erro ao mover o arquivo.');
        } catch (Exception $e) {
            error_log('Erro no upload da logo: ' . $e->getMessage());
            throw $e;
        }
    }

    public static function deleteImage($imagePath)
    {
        $fullPath = __DIR__ . "/../" . $imagePath;
        return file_exists($fullPath) ? unlink($fullPath) : false;
    }

    /**
     * Verifica se o arquivo é uma imagem válida.
     */
    private static function isValidImage($tmpName)
    {
        return getimagesize($tmpName) !== false;
    }

    /**
     * Verifica se o tipo MIME da imagem é permitido.
     */
    private static function isAllowedType($type)
    {
        return in_array($type, self::$allowedTypes);
    }

    /**
     * Verifica se o tamanho da imagem está dentro do limite permitido.
     */
    private static function isAllowedSize($size)
    {
        return $size <= self::$maxSize;
    }
}

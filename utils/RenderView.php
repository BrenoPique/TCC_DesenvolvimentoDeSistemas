<?php
class RenderView
{
    public static function loadView($view, $viewData = [])
    {
        extract($viewData);

        ob_start();
        include __DIR__ . "/../views/{$view}.php";
        $content = ob_get_clean();

        return array_merge($viewData, ['content' => $content]);
    }
}

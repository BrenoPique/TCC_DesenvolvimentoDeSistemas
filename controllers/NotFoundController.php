<?php
require_once __DIR__ . '/../utils/RenderView.php';
class NotFoundController
{
    public function index()
    {
        return RenderView::loadView('notFound', []);
    }
}

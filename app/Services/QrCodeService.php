<?php

namespace App\Services;

use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class QrCodeService
{
    public function generateSvg(string $data, int $size = 250): string
    {
        $renderer = new ImageRenderer(
            new RendererStyle(max(100, $size)),
            new SvgImageBackEnd()
        );

        return (new Writer($renderer))->writeString($data);
    }

    public function generateDataUri(string $data, int $size = 250): string
    {
        $svg = $this->generateSvg($data, $size);

        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }
}
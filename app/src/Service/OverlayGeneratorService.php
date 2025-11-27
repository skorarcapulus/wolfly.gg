<?php

namespace App\Service;

use App\Entity\Overlay;
use App\Entity\Project;

class OverlayGeneratorService
{
    public function __construct()
    {
        // Constructor code here
    }

    public function generateOverlay(Project $project, Overlay $overlay): void
    {
        $documents = $overlay->getDocuments();
        $folderPath = $this->getFolderPath($project, $overlay);

        foreach ($documents as $document) {
            if ($document->isReleased()) {
                $sourceCode = $document->getSource();
                $filePath = sprintf('%s/%s.%s', $folderPath, $document->getTitle(), $document->getType()->value);

                file_put_contents($filePath, $sourceCode);
            }
        }
    }

    public function getFolderPath(Project $project, Overlay $overlay): string
    {
        $folderPath = sprintf('../templates/_generated/%s/overlays/%s', $project->getId(), $overlay->getId());
        if (!is_dir($folderPath)) {
            mkdir($folderPath, 0755, true);
        }

        return $folderPath;
    }
}
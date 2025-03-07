<?php

declare(strict_types=1);

namespace App\Project\CatrobatCode\Parser\Bricks;

use App\Project\CatrobatCode\Parser\Constants;

class HideBrick extends Brick
{
  #[\Override]
  protected function create(): void
  {
    $this->type = Constants::HIDE_BRICK;
    $this->caption = 'Hide';
    $this->setImgFile(Constants::LOOKS_BRICK_IMG);
  }
}

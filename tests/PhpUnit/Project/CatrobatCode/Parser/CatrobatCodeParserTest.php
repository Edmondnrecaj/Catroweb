<?php

declare(strict_types=1);

namespace Tests\PhpUnit\Project\CatrobatCode\Parser;

use App\Project\CatrobatCode\Parser\CatrobatCodeParser;
use App\Project\CatrobatCode\Parser\ParsedSceneProject;
use App\Project\CatrobatCode\Parser\ParsedSimpleProject;
use App\Project\CatrobatFile\ExtractedCatrobatFile;
use App\Storage\FileHelper;
use App\System\Testing\PhpUnit\Extension\BootstrapExtension;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(CatrobatCodeParser::class)]
class CatrobatCodeParserTest extends TestCase
{
  protected CatrobatCodeParser $parser;

  #[\Override]
  protected function setUp(): void
  {
    $this->parser = new CatrobatCodeParser();
  }

  /**
   * @throws \Exception
   */
  #[\Override]
  protected function tearDown(): void
  {
    FileHelper::emptyDirectory(BootstrapExtension::$CACHE_DIR);
  }

  #[DataProvider('provideValidProgramData')]
  public function testMustReturnParsedProgram(mixed $extracted_catrobat_program): void
  {
    $actual = $this->parser->parse($extracted_catrobat_program);
    $expected = [
      ParsedSimpleProject::class,
      ParsedSceneProject::class,
    ];

    $this->assertThat($actual, $this->logicalOr(
      $this->isInstanceOf($expected[0]),
      $this->isInstanceOf($expected[1])
    ));
  }

  public function testMustReturnParsedSimpleProgramIfNoScenes(): void
  {
    $extracted_catrobat_program = new ExtractedCatrobatFile(BootstrapExtension::$FIXTURES_DIR.'ValidPrograms/SimpleProgram/', '', '');
    $actual = $this->parser->parse($extracted_catrobat_program);
    $expected = ParsedSimpleProject::class;

    $this->assertInstanceOf($expected, $actual);
  }

  public function testMustReturnParsedSceneProgramIfScenes(): void
  {
    $extracted_catrobat_program = new ExtractedCatrobatFile(BootstrapExtension::$FIXTURES_DIR.'ValidPrograms/SceneProgram/', '', '');
    $actual = $this->parser->parse($extracted_catrobat_program);
    $expected = ParsedSceneProject::class;

    $this->assertInstanceOf($expected, $actual);
  }

  #[DataProvider('provideFaultyProgramData')]
  public function testMustReturnNullOnError(ExtractedCatrobatFile $faulty_program): void
  {
    $this->assertNull($this->parser->parse($faulty_program));
  }

  /**
   * @return ExtractedCatrobatFile[][]
   */
  public static function provideValidProgramData(): array
  {
    return [[new ExtractedCatrobatFile(BootstrapExtension::$FIXTURES_DIR.'ValidPrograms/SimpleProgram/', '', '')], [new ExtractedCatrobatFile(BootstrapExtension::$FIXTURES_DIR.'ValidPrograms/SceneProgram/', '', '')], [new ExtractedCatrobatFile(BootstrapExtension::$FIXTURES_DIR.'ValidPrograms/AllBricksProgram/', '', '')]];
  }

  public static function provideFaultyProgramData(): \Generator
  {
    yield [
      new ExtractedCatrobatFile(
        BootstrapExtension::$FIXTURES_DIR.'FaultyPrograms/CorruptedGroupFaultyProgram/', '', ''),
    ];
    yield [
      new ExtractedCatrobatFile(
        BootstrapExtension::$FIXTURES_DIR.'FaultyPrograms/ScenesWithoutNamesFaultyProgram/', '', ''),
    ];
  }
}

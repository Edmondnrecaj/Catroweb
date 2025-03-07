<?php

declare(strict_types=1);

namespace App\Project\CatrobatCode\Statements;

class DeleteItemOfUserListStatement extends BaseUserListStatement
{
  final public const string BEGIN_STRING = 'delete item in userlist ';

  final public const string MIDDLE_STRING = '(';

  final public const string END_STRING = ')<br/>';

  public function __construct(mixed $statementFactory, mixed $xmlTree, mixed $spaces)
  {
    parent::__construct($statementFactory, $xmlTree, $spaces,
      self::BEGIN_STRING,
      self::MIDDLE_STRING,
      self::END_STRING);
  }

  public function getBrickText(): string
  {
    $list_variable_name = $this->xmlTree->userList->name;

    $formula_string = $this->getFormulaListChildStatement()->executeChildren();
    $formula_string_without_markup = preg_replace('#<[^>]*>#', '', $formula_string);

    return 'Delete item from list '.$list_variable_name.' at position '.$formula_string_without_markup;
  }

  public function getBrickColor(): string
  {
    return '1h_brick_red.png';
  }
}

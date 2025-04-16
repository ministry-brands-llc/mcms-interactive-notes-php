<?php

namespace MonkDev\InteractiveNote\Tests;

use PHPUnit\Framework\TestCase;
use MonkDev\InteractiveNote\InteractiveNote;

class InteractiveNoteTest extends TestCase
{
    /** @test */
    public function test_it_parses_a_block_of_text_for_single_line_input_placeholders()
    {
        $unparsedText = file_get_contents(__DIR__ . '/stubs/unparsed-single-line-text.stub');
        $parsedText = file_get_contents(__DIR__ . '/stubs/parsed-single-line-text.stub');

        $note = new InteractiveNote($unparsedText);
        $note->disableAutoWidth();
        $note->enableLastPass();

        $this->assertEquals($parsedText, $note->parse());
    }

    /** @test */
    public function test_it_parses_a_block_of_text_for_free_form_text_input_placeholders()
    {
        $unparsedText = file_get_contents(__DIR__ . '/stubs/unparsed-free-form-text.stub');
        $parsedText = file_get_contents(__DIR__ . '/stubs/parsed-free-form-text.stub');
        $note = new InteractiveNote($unparsedText);
        $note->disableAutoWidth();
        $note->enableLastPass();

        $this->assertEquals($parsedText, $note->parse());
    }

    /** @test */
    public function test_it_can_parse_html_without_issue()
    {
        $unparsedHtml = file_get_contents(__DIR__ . '/stubs/unparsed-html.stub');
        $parsedHtml = file_get_contents(__DIR__ . '/stubs/parsed-html.stub');
        $note = new InteractiveNote($unparsedHtml);
        $note->disableAutoWidth();
        $note->enableLastPass();

        $this->assertEquals($parsedHtml, $note->parse());
    }

    /** @test */
    public function test_you_can_override_the_default_single_input_template()
    {
        $note = new InteractiveNote('{{hello world}}');
        $note->disableAutoWidth();
        $note->enableLastPass();
        $note->setSingleInputTemplate("<input class='override' type='text' required='required' data-corect='__ANSWER__'>");

        $this->assertEquals("<input class='override' type='text' required='required' data-corect='hello world'>", $note->parse());
    }

    /** @test */
    public function test_you_can_override_the_default_free_form_template()
    {
        $note = new InteractiveNote('{## hello world ##}');
        $note->setFreeFormTemplate("<textarea class='override' data-hint='__ANSWER__'></textarea><p class='hint'>__ANSWER__</p>");

        $this->assertEquals("<textarea class='override' data-hint='hello world'></textarea><p class='hint'>hello world</p>", $note->parse());
    }

    /** @test */
    public function test_you_can_override_the_javascript_data_answer_attribute()
    {
        $note = new InteractiveNote('{{ hello world }}');
        $this->assertStringContainsString('data-answer', $note->getJavascriptSnippet());
        $this->assertStringNotContainsString('data-correct', $note->getJavascriptSnippet());

        $note->setSingleInputDataAnswerAttributeName('data-correct');

        $this->assertStringNotContainsString('data-answer', $note->getJavascriptSnippet());
        $this->assertStringContainsString('data-correct', $note->getJavascriptSnippet());
    }

    /** @test */
    public function test_you_can_override_the_javascript_correct_answer_class()
    {
        $note = new InteractiveNote('{{ hello world }}');
        $this->assertStringContainsString('correct-answer', $note->getJavascriptSnippet());
        $this->assertStringNotContainsString('answer-is-correct', $note->getJavascriptSnippet());

        $note->setCorrectAnswerClass('answer-is-correct');

        $this->assertStringNotContainsString('correct-answer', $note->getJavascriptSnippet());
        $this->assertStringContainsString('answer-is-correct', $note->getJavascriptSnippet());
    }

    /** @test */
    public function test_you_can_override_the_javascript_wrong_answer_class()
    {
        $note = new InteractiveNote('{{ hello world }}');
        $this->assertStringContainsString('wrong-answer', $note->getJavascriptSnippet());
        $this->assertStringNotContainsString('answer-is-wrong', $note->getJavascriptSnippet());

        $note->setWrongAnswerClass('answer-is-wrong');

        $this->assertStringNotContainsString('wrong-answer', $note->getJavascriptSnippet());
        $this->assertStringContainsString('answer-is-wrong', $note->getJavascriptSnippet());
    }

    /** @test */
    public function test_you_can_override_the_css_free_form_text_color()
    {
        $note = new InteractiveNote('{{ hello world }}');
        $this->assertStringContainsString('color: blue;', $note->getCssSnippet());
        $this->assertStringNotContainsString('color: red;', $note->getCssSnippet());

        $note->setFreeFormTextColor('red');

        $this->assertStringNotContainsString('color: blue;', $note->getCssSnippet());
        $this->assertStringContainsString('color: red;', $note->getCssSnippet());
    }

    /** @test */
    public function test_you_can_override_the_css_input_text_color()
    {
        $note = new InteractiveNote('{{ hello world }}');
        $this->assertStringContainsString('color: #c90;', $note->getCssSnippet());
        $this->assertStringNotContainsString('color: #ccc;', $note->getCssSnippet());

        $note->setInputTextColor('#ccc');

        $this->assertStringNotContainsString('color: #c90;', $note->getCssSnippet());
        $this->assertStringContainsString('color: #ccc;', $note->getCssSnippet());
    }

    /** @test */
    public function test_you_can_override_the_css_correct_answer_color()
    {
        $note = new InteractiveNote('{{ hello world }}');
        $this->assertStringContainsString('color: #090;', $note->getCssSnippet());
        $this->assertStringContainsString('border-color: #090;', $note->getCssSnippet());
        $this->assertStringNotContainsString('color: #023;', $note->getCssSnippet());
        $this->assertStringNotContainsString('border-color: #023;', $note->getCssSnippet());

        $note->setCorrectAnswerColor('#023');

        $this->assertStringNotContainsString('color: #090;', $note->getCssSnippet());
        $this->assertStringNotContainsString('border-color: #090;', $note->getCssSnippet());
        $this->assertStringContainsString('color: #023;', $note->getCssSnippet());
        $this->assertStringContainsString('border-color: #023;', $note->getCssSnippet());
    }

    /** @test */
    public function test_you_can_override_the_css_wrong_answer_color()
    {
        $note = new InteractiveNote('{{ hello world }}');
        $this->assertStringContainsString('color: #f00;', $note->getCssSnippet());
        $this->assertStringContainsString('border-color: #f00;', $note->getCssSnippet());
        $this->assertStringNotContainsString('color: #55C;', $note->getCssSnippet());
        $this->assertStringNotContainsString('border-color: #55C;', $note->getCssSnippet());

        $note->setWrongAnswerColor('#55C');

        $this->assertStringNotContainsString('color: #f00;', $note->getCssSnippet());
        $this->assertStringNotContainsString('border-color: #f00;', $note->getCssSnippet());
        $this->assertStringContainsString('color: #55C;', $note->getCssSnippet());
        $this->assertStringContainsString('border-color: #55C;', $note->getCssSnippet());
    }

    /** @test */
    public function test_you_can_disable_auto_width_functionality()
    {
        $note = new InteractiveNote('{{ hello world }}');

        $this->assertStringContainsString("style='width:7.7em;'", $note->parse());

        $note->disableAutoWidth();
        $note->enableLastPass();

        $this->assertStringNotContainsString("style='width:", $note->parse());

    }
}

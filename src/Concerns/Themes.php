<?php

namespace GSManager\Prompts\Concerns;

use InvalidArgumentException;
use GSManager\Prompts\Clear;
use GSManager\Prompts\ConfirmPrompt;
use GSManager\Prompts\MultiSearchPrompt;
use GSManager\Prompts\MultiSelectPrompt;
use GSManager\Prompts\Note;
use GSManager\Prompts\PasswordPrompt;
use GSManager\Prompts\PausePrompt;
use GSManager\Prompts\Progress;
use GSManager\Prompts\SearchPrompt;
use GSManager\Prompts\SelectPrompt;
use GSManager\Prompts\Spinner;
use GSManager\Prompts\SuggestPrompt;
use GSManager\Prompts\Table;
use GSManager\Prompts\TextareaPrompt;
use GSManager\Prompts\TextPrompt;
use GSManager\Prompts\Themes\Default\ClearRenderer;
use GSManager\Prompts\Themes\Default\ConfirmPromptRenderer;
use GSManager\Prompts\Themes\Default\MultiSearchPromptRenderer;
use GSManager\Prompts\Themes\Default\MultiSelectPromptRenderer;
use GSManager\Prompts\Themes\Default\NoteRenderer;
use GSManager\Prompts\Themes\Default\PasswordPromptRenderer;
use GSManager\Prompts\Themes\Default\PausePromptRenderer;
use GSManager\Prompts\Themes\Default\ProgressRenderer;
use GSManager\Prompts\Themes\Default\SearchPromptRenderer;
use GSManager\Prompts\Themes\Default\SelectPromptRenderer;
use GSManager\Prompts\Themes\Default\SpinnerRenderer;
use GSManager\Prompts\Themes\Default\SuggestPromptRenderer;
use GSManager\Prompts\Themes\Default\TableRenderer;
use GSManager\Prompts\Themes\Default\TextareaPromptRenderer;
use GSManager\Prompts\Themes\Default\TextPromptRenderer;

trait Themes
{
    /**
     * The name of the active theme.
     */
    protected static string $theme = 'default';

    /**
     * The available themes.
     *
     * @var array<string, array<class-string<\GSManager\Prompts\Prompt>, class-string<object&callable>>>
     */
    protected static array $themes = [
        'default' => [
            TextPrompt::class => TextPromptRenderer::class,
            TextareaPrompt::class => TextareaPromptRenderer::class,
            PasswordPrompt::class => PasswordPromptRenderer::class,
            SelectPrompt::class => SelectPromptRenderer::class,
            MultiSelectPrompt::class => MultiSelectPromptRenderer::class,
            ConfirmPrompt::class => ConfirmPromptRenderer::class,
            PausePrompt::class => PausePromptRenderer::class,
            SearchPrompt::class => SearchPromptRenderer::class,
            MultiSearchPrompt::class => MultiSearchPromptRenderer::class,
            SuggestPrompt::class => SuggestPromptRenderer::class,
            Spinner::class => SpinnerRenderer::class,
            Note::class => NoteRenderer::class,
            Table::class => TableRenderer::class,
            Progress::class => ProgressRenderer::class,
            Clear::class => ClearRenderer::class,
        ],
    ];

    /**
     * Get or set the active theme.
     *
     * @throws \InvalidArgumentException
     */
    public static function theme(?string $name = null): string
    {
        if ($name === null) {
            return static::$theme;
        }

        if (! isset(static::$themes[$name])) {
            throw new InvalidArgumentException("Prompt theme [{$name}] not found.");
        }

        return static::$theme = $name;
    }

    /**
     * Add a new theme.
     *
     * @param  array<class-string<\GSManager\Prompts\Prompt>, class-string<object&callable>>  $renderers
     */
    public static function addTheme(string $name, array $renderers): void
    {
        if ($name === 'default') {
            throw new InvalidArgumentException('The default theme cannot be overridden.');
        }

        static::$themes[$name] = $renderers;
    }

    /**
     * Get the renderer for the current prompt.
     */
    protected function getRenderer(): callable
    {
        $class = get_class($this);

        return new (static::$themes[static::$theme][$class] ?? static::$themes['default'][$class])($this);
    }

    /**
     * Render the prompt using the active theme.
     */
    protected function renderTheme(): string
    {
        $renderer = $this->getRenderer();

        return $renderer($this);
    }
}

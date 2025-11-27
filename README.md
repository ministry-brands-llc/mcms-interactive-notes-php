# Interactive Notes

A PHP Library to transform HTML into an interactive fill the in blank style form.

## Installation via Composer

This package is hosted in a private Azure DevOps Git & GitHub public repository.

You can install this Composer package from Azure DevOps or GitHub using following options:
- HTTPS with a Personal Access Token (PAT), or
- SSH with an SSH key

### Option 1: Install via HTTPS + Personal Access Token (PAT)

#### 1. Add the repository to your composer.json:
```json
{
    "repositories": [
        {
        "type": "vcs",
        "url": "https://github.com/ministry-brands-llc/mcms-interactive-notes-php.git"
        }
    ],
    "require": {
        "monkdev/mcms-interactive-note-php": "~0.0.3"
    }
}
```

### Install the package:
```bash
composer install
```
### Option 3: Install via HTTPS from GitHub

This package is available as a public repository on GitHub. No authentication required.

#### 1. Add the repository to your composer.json:
```json
{
    "repositories": [
        {
        "type": "vcs",
        "url": "https://github.com/ministry-brands-llc/mcms-interactive-notes-php.git"
        }
    ],
    "require": {
        "monkdev/mcms-interactive-note-php": "~0.0.3"
    }
}
```

#### 2. Install the package:
```bash
composer install
```

### Option 4: Install via SSH from GitHub (Optional)

This option is for users who prefer using SSH for their Git operations. SSH key setup is optional for public repositories.

#### 1. (Optional) Ensure your SSH key is created and added to GitHub

If you prefer to use SSH, make sure you have an SSH key configured and added to your GitHub account.

#### 2. Add the repository to your composer.json using the SSH URL:
```json
{
    "repositories": [
        {
        "type": "vcs",
        "url": "git@github.com:ministry-brands-llc/mcms-interactive-notes-php.git"
        }
    ],
    "require": {
        "monkdev/mcms-interactive-note-php": "~0.0.3"
    }
}
```

#### 3. Install the package:
```bash
composer install
```

## Installation into an existing MCMS Site

#### File install:
1. Copy the entire contents of `src/` to a new folder in the site: `_components/interactive_notes/` (has the class and `assets` folder)

#### Implementation into site:

1. In the `mcms_*` page where you'll be implementing interactive notes, make sure you've got the sermon/livestream detail with the `interactivenote` content.  Use the following code for ideas:
    ```php
    $interactive_note = getContent(
        "sermon",
        "display:auto",
        "show_detail:__interactivenote__",
        "noecho"
    );

    require_once($_SERVER["DOCUMENT_ROOT"] . "/_components/interactive_notes/InteractiveNote.php");
    $notes = new MonkDev\InteractiveNote\InteractiveNote(file_get_contents($interactive_note));

    if( !empty($interactive_note) ) {
        $notes->setSingleInputTemplate("<input name='single-line[]' class='blank form-control single-line' data-answer='__ANSWER__' type='text'>");
        $notes->setFreeFormTemplate("<textarea name='free-form[]' class='pnoteText form-control free-form w-100' cols='30' rows='10' data-answer='__ANSWER__' placeholder='__ANSWER__'></textarea>");
        $notes->setCorrectAnswerClass('is-valid');
        $notes->setWrongAnswerClass('is-invalid');
        $notes->disableAutoWidth();
    }
    ```
1. Get the notes into the page. Use something like the following, matching the page's style, but leave the `autofill`, `clearnotes`, and `saveAsPdf` classes on the buttons:
    ```html
    <?php if( !empty($notes->parse() ) : ?>
    <div class="row">
        <div class="col-md-8 offset-2 my-5">
            <h1 id="notes-title">Notes</h1>
            <div>
                <form id="notes" class="form-inline">
                    <?= $notes->parse(); ?>
                </form>
                <button href="javascript:void(0);" class="autofill btn btn-outline-secondary">Fill in the answers for me</button>
                <button href="javascript:void(0);" class="clearnotes btn btn-outline-secondary">Start Over</button>
                <button href="javascript:void(0);" class="saveAsPdf btn btn-outline-secondary">Save as PDF</button>
            </div>
        </div>
    </div>
    <?php endif; ?>
    ```
1. Somewhere after the `_components/scripts.php` include, you'll need to add the following to make the UI work:
    ```html
    <?= $notes->getCssSnippet(); ?>
    <?= $notes->getJavascriptSnippet(); ?>
    ```

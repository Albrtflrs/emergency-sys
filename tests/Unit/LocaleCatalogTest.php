<?php

namespace Tests\Unit;

use App\Support\I18n\LocaleCatalog;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class LocaleCatalogTest extends TestCase
{
    public function test_it_registers_a_locale_when_a_translation_file_is_added(): void
    {
        $path = lang_path('zz-test.json');
        File::put($path, json_encode(['__language_name' => 'Test language', 'Hello' => 'Hello there']));

        try {
            $catalog = app(LocaleCatalog::class);

            $this->assertTrue($catalog->supports('zz-test'));
            $this->assertSame('Test language', $catalog->all()['zz-test']);
            $this->assertSame('Hello there', __('Hello', [], 'zz-test'));

            $html = view('public.language-switcher')->render();
            $this->assertStringContainsString('value="zz-test"', $html);
            $this->assertStringContainsString('Test language', $html);
        } finally {
            File::delete($path);
        }
    }
}

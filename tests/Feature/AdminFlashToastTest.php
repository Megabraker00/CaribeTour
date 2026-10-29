<?php

namespace Tests\Feature;

use Tests\TestCase;

class AdminFlashToastTest extends TestCase
{
    public function test_admin_save_message_is_a_fixed_toast(): void
    {
        session()->flash('success', 'Categoría creada');

        $html = view('admin.partials.flash')->render();

        $this->assertStringContainsString('admin-toast-stack', $html);
        $this->assertStringContainsString('position: fixed', $html);
        $this->assertStringContainsString('Categoría creada', $html);
        $this->assertStringNotContainsString('js-auto-dismiss', $html);
    }
}

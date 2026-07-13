<?php

use App\Models\HomepageVideo;

it('flashes a notify toast (not a plain session flash) when related products are updated', function () {
    $this->actingAs(adminUser())
        ->post(route('admin.related.products.update'), ['related_ids' => []])
        ->assertRedirect()
        ->assertSessionHas('notify.type', 'success')
        ->assertSessionHas('notify.model', 'toast')
        ->assertSessionHas('notify.message', 'Related products updated.');
});

it('flashes a notify toast when a homepage video is deleted', function () {
    $video = HomepageVideo::create(['title' => 'Intro']);

    $this->actingAs(adminUser())
        ->delete(route('admin.video.destroy', $video))
        ->assertRedirect()
        ->assertSessionHas('notify.model', 'toast')
        ->assertSessionHas('notify.message', 'Deleted');
});

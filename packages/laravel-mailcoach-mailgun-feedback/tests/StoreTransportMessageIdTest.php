<?php

namespace Spatie\MailcoachMailgunFeedback\Tests;

use Illuminate\Mail\Events\MessageSent;
use Spatie\Mailcoach\Domain\Shared\Models\Send;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Part\TextPart;

class StoreTransportMessageIdTest extends TestCase
{
    /** @test * */
    public function it_stores_the_message_id_from_the_transport()
    {
        $pendingSend = Send::factory()->create();
        $message = (new Email())->setBody(new TextPart('body'));
        $message->getHeaders()->addTextHeader('X-Mailgun-Message-ID', '1234');

        event(new MessageSent($message, [
            'send' => $pendingSend,
        ]));

        tap($pendingSend->fresh(), function (Send $send) {
            $this->assertEquals('1234', $send->transport_message_id);
        });
    }
}

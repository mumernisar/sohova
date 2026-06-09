<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();

            // Raw fields captured from the contact form
            $table->string('name');
            $table->string('email')->index();
            $table->string('phone')->nullable();
            $table->string('company')->nullable();
            $table->text('message')->nullable();

            // Enriched fields (filled in by the n8n enrichment step)
            $table->string('company_domain')->nullable();
            $table->unsignedInteger('company_size')->nullable();
            $table->string('company_industry')->nullable();

            // pending | enriched | failed
            $table->string('enrichment_status')->default('pending');

            // Lead lifecycle managed by the sales team in the panel
            // new | contacted | qualified | disqualified
            $table->string('status')->default('new');

            $table->string('source')->default('contact_form');
            $table->timestamp('welcome_email_sent_at')->nullable();

            // Keep the original payload for auditing / debugging
            $table->json('raw_payload')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};

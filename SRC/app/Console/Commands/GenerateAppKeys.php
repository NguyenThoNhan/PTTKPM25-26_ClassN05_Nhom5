<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateAppKeys extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-keys';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the private and public keys for digital signatures';

    /**
     * Execute the console command.
     */
    public function handle()
    {
         $keyPath = storage_path('app/keys');

        if (!File::isDirectory($keyPath)) {
            File::makeDirectory($keyPath, 0755, true);
        }

        $privateKeyPath = $keyPath . '/private.key';
        $publicKeyPath = $keyPath . '/public.key';

        if (File::exists($privateKeyPath) || File::exists($publicKeyPath)) {
            if (!$this->confirm('Keys already exist. Do you want to overwrite them?')) {
                $this->info('Key generation cancelled.');
                return 0;
            }
        }

        $config = [
            "digest_alg" => "sha512",
            "private_key_bits" => 2048,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        ];

        // Create the private and public key
        $res = openssl_pkey_new($config);

        if (!$res) {
            $this->error('Failed to generate new private key.');
            return 1;
        }

        // Extract the private key
        openssl_pkey_export($res, $privateKey);
        File::put($privateKeyPath, $privateKey);

        // Extract the public key
        $publicKeyDetails = openssl_pkey_get_details($res);
        $publicKey = $publicKeyDetails["key"];
        File::put($publicKeyPath, $publicKey);
        
        // Set permissions for the private key
        chmod($privateKeyPath, 0600);

        $this->info('Private and public keys have been generated successfully.');
        $this->comment('Private key stored at: ' . $privateKeyPath);
        $this->comment('Public key stored at: ' . $publicKeyPath);

        return 0;
    }
}

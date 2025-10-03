<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class QRCodeService
{
    /**
     * Generate QR code for book
     */
    public function generateBookQRCode($bookId, $bookTitle)
    {
        $qrData = route('books.show', $bookId);
        $qrCode = $this->generateQRCode($qrData, $bookTitle);
        
        return $qrCode;
    }

    /**
     * Generate QR code using simple method (for demo)
     */
    private function generateQRCode($data, $title)
    {
        // For demo purposes, we'll create a simple QR code representation
        // In production, you would use a proper QR code library
        
        $qrCodeData = [
            'data' => $data,
            'title' => $title,
            'generated_at' => now()->toISOString(),
        ];
        
        return base64_encode(json_encode($qrCodeData));
    }

    /**
     * Save QR code to storage
     */
    public function saveQRCode($bookId, $qrCodeData)
    {
        $filename = "qr-codes/book-{$bookId}.json";
        Storage::disk('public')->put($filename, $qrCodeData);
        
        return $filename;
    }

    /**
     * Get QR code for book
     */
    public function getBookQRCode($bookId)
    {
        $filename = "qr-codes/book-{$bookId}.json";
        
        if (Storage::disk('public')->exists($filename)) {
            return Storage::disk('public')->get($filename);
        }
        
        return null;
    }

    /**
     * Generate QR code URL for scanning
     */
    public function generateQRCodeURL($bookId)
    {
        return route('books.qr-scan', $bookId);
    }
}

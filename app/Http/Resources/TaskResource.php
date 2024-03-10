<?php

namespace App\Http\Resources;

use Aws\S3\S3Client;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $url = null;

        if ($this->documents) {
            // Initialize AWS SDK
            $s3Client = new S3Client([
                'version' => 'latest',
                'region'  => env('AWS_DEFAULT_REGION'),
                'credentials' => [
                    'key'    => env('AWS_ACCESS_KEY_ID'),
                    'secret' => env('AWS_SECRET_ACCESS_KEY'),
                ],
            ]);

            // Generate pre-signed URL with a 1-hour expiration time
            $command = $s3Client->getCommand('GetObject', [
                'Bucket' => env('AWS_BUCKET'),
                'Key'    => $this->documents,
            ]);

            $presignedUrl = $s3Client->createPresignedRequest($command, '+1 hour')->getUri();

            $url = (string) $presignedUrl;
        }
        return [
            'id' => $this->id,
            'event_id' => $this->event_id,
            'name' => $this->name,
            'documents' => $url,
            'status' => $this->status,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}

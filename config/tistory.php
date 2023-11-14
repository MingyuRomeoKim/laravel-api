<?php

return [
    'client_id' => env('MG_API_TISTORY_CLIENT_ID'),
    'client_secret' => env('MG_API_TISTORY_CLIENT_SECRET'),
    'redirect_url' => env('MG_API_TISTORY_REDIRECT_URL', 'http://127.0.0.1:8000/api/v1/tistory/accessToken'),
    'grant_type' => env('MG_API_TISTORY_GRANT_TYPE', 'authorization_code'),
    'output_type' => env('MG_API_TISTORY_OUTPUT_TYPE', 'json'),
    'output_data_path' => env('MG_API_TISTORY_DATA_PATH','')
];

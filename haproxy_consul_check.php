<?php
/**
 * Tests an HAProxy monitor-uri specified in $argv[1] for HTTP 200
 */
require __DIR__ . '/vendor/autoload.php';

function report($code, $msg) {
  fwrite(STDERR, $msg . "\n");
  exit($code);
}

if (count($argv) != 2) {
  report (1, 'Health check script was called incorrectly.');
}

$health_uri = $argv[1];

$client = new GuzzleHttp\Client();
try {
  /**
   * @var $res Psr\Http\Message\ResponseInterface
   */
  $res = $client->get($health_uri,
    [
      'timeout' => 5,
    ]
  );
} catch (Guzzle\Http\Exception\BadResponseException $e) {
  report (2, "HTTP monitor URI not ok: " . $e->getMessage());
}

if (!$res->getStatusCode() == 200) {
  report (2, "HTTP monitor URI not 200 OK.");
}

report (0, 'HTTP monitor URI ok.');

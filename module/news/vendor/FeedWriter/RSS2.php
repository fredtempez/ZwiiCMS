<?php
namespace FeedWriter; /**
 * Wrapper for creating RSS2 feeds
 *
 * @package     UniversalFeedWriter
 */
class RSS2 extends Feed { public function __construct() { parent::__construct(Feed::RSS2); } }

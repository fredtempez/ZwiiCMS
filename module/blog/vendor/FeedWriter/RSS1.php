<?php
namespace FeedWriter; /**
 * Wrapper for creating RSS1 feeds
 *
 * @package     UniversalFeedWriter
 */
class RSS1 extends Feed { public function __construct() { parent::__construct(Feed::RSS1); } }

<?php
namespace FeedWriter; /**
 * Wrapper for creating ATOM feeds
 *
 * @package     UniversalFeedWriter
 */
class ATOM extends Feed { public function __construct() { parent::__construct(Feed::ATOM); } }

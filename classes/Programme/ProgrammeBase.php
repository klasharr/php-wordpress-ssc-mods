<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

require_once( 'EventDTO.php' );
require_once( 'Day.php' );
require_once( 'display/FullEventsTable.php' );
require_once( 'display/EventsPage.php' );
require_once( 'SSCProgrammeFactory.php' );

class ProgrammeBase {

	/**
	 * @var SafetyTeams
	 */
	private $safetyTeams;

	/**
	 * @var SailType
	 */
	private $sailType;

	/**
	 * @var SailTypeFilter
	 */
	private $sailFilter;

	/**
	 * @var RaceSeries
	 */
	private $raceSeries;

	/**
	 * @var $allDuties array
	 */
	protected $allDuties;

	/**
	 * @var $flattenedEvents array
	 */
	protected $flattenedEvents;


	public function __construct() {

		$this->safetyTeams = SSCProgrammeFactory::getSafetyTeams();
		$this->sailType    = SSCProgrammeFactory::getSailType();
		$this->sailFilter  = SSCProgrammeFactory::getSailTypeFilter();
		$this->raceSeries  = SSCProgrammeFactory::getRaceSeries();

	}

	public function __invoke( $args ) {

		if ( empty( $args[0] ) || (int) $args[0] === 0 ) {
			throw new Exception( 'The first argument must be a non zero integer value' );
		}

		$post = $this->getPost( $args[0] );

		$this->flattenedEvents = $this->getEvents( $post );

	}

	/**
	 * @param $post_id
	 *
	 * @return array|null|WP_Post
	 * @throws Exception
	 */
	protected function getPost( $post_id ) {

		$post = get_post( $post_id );

		if ( false === $post instanceof WP_Post ) {
			throw new Exception( 'Post with ID %d does not exist.', $post_id );
		}

		if ( $post->post_type != 'sailing-programme' ) {
			throw new Exception( 'The post ID passed must be a post type: sailing-programme' );
		}

		if ( empty( $post->post_content ) ) {
			throw new Exception( sprintf( 'Sailing Programme with Post ID %d has no content.', $post_id ) );
		}

		return $post;

	}

	/**
	 * @param WP_Post $post
	 */
	protected function getEvents( WP_Post $post ) {

		/**
		 * @var $contentParser ContentParser
		 */
		$contentParser = new ContentParser( $post->post_content, $this->sailType, $this->raceSeries, $this->safetyTeams );

		$eventsData = $contentParser->getData( $this->sailFilter );

		$eventsDataFlattened = array();

		foreach ( $eventsData['data'] as $date => $events ) {

			/**
			 * @var EventDTO $EventDTO
			 */
			foreach ( $events as $EventDTO ) {
				$eventsDataFlattened[] = $EventDTO;
			}
		}

		return $eventsDataFlattened;
	}


}
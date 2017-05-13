<?php
/**
 * Created by PhpStorm.
 * User: maks
 * Date: 28.10.16
 * Time: 12:28
 */

namespace Generator\Services;

use MapUtils;
use TargetingIdeaSelector;
use AdWordsUser;
use RelatedToQuerySearchParameter;
use LanguageSearchParameter;
use Language;
use NetworkSetting;
use NetworkSearchParameter;
use Paging;
use LocationSearchParameter;
use Location;
use Selector;
use Predicate;

/**
 * Class AdWords
 * @package Generator\Services
 */
class AdWords
{
    /**
     * @param AdWordsUser $user
     * @param $keyword
     * @param int $location
     * @return array
     */
    private function call(AdWordsUser $user, $keyword, $location = 2276)
	{
		$out = array();
		$targetingIdeaService = $user->GetService('TargetingIdeaService', 'v201609');

		$selector = new TargetingIdeaSelector();
		$selector->requestType = 'STATS';
		$selector->ideaType = 'KEYWORD';
		$selector->requestedAttributeTypes = array(
			'KEYWORD_TEXT',
			'SEARCH_VOLUME'
		);

		$relatedToQuerySearchParameter = new RelatedToQuerySearchParameter();
		$relatedToQuerySearchParameter->queries = array($keyword);
		$selector->searchParameters[] = $relatedToQuerySearchParameter;

//		Choose german language
		$languageParameter = new LanguageSearchParameter();
		$de = new Language();
		$de->id = 1001;
		$languageParameter->languages = array($de);
		$selector->searchParameters[] = $languageParameter;

//		Choose results from whole Germany
		$locParamerer = new LocationSearchParameter();
		$loc = new Location();
		$loc->id = $location;
		$locParamerer->locations = array($loc);
		$selector->searchParameters[] = $locParamerer;

//      Search params
		$networkSetting = new NetworkSetting();
		$networkSetting->targetGoogleSearch = true;
		$networkSetting->targetSearchNetwork = false;
		$networkSetting->targetContentNetwork = false;
		$networkSetting->targetPartnerSearchNetwork = false;

		$networkSearchParameter = new NetworkSearchParameter();
		$networkSearchParameter->networkSetting = $networkSetting;
		$selector->searchParameters[] = $networkSearchParameter;

//		Set selector paging (required by this service).
		$selector->paging = new Paging(0, 1);

		// Make the get request.
		$page = $targetingIdeaService->get($selector);

		if (isset($page->entries)) {
			foreach ($page->entries as $targetingIdea) {
				$data          = MapUtils::GetMap( $targetingIdea->data );
				$keyword       = $data['KEYWORD_TEXT']->value;
				$search_volume = isset( $data['SEARCH_VOLUME']->value )
					? $data['SEARCH_VOLUME']->value : 0;
				$out[] = [$keyword => $search_volume];
			}
		} else {
			$out[] = [$keyword => '0'];
		}

		return $out;
	}

    /**
     * @param AdWordsUser $user
     * @param $city
     * @return null
     */
    private function getLocation(AdWordsUser $user, $city)
	{
		$location = null;
		$locationCriterionService =
			$user->GetService('LocationCriterionService', 'v201609');

		// Location names to look up.
		$locationNames = array($city);

		// Locale to retrieve location names in.
		$locale = 'de';

		$selector = new Selector();
		$selector->fields = array('Id', 'LocationName', 'CanonicalName',
			'DisplayType',  'ParentLocations', 'Reach', 'TargetingStatus');

		// Location names must match exactly, only EQUALS and IN are supported.
		$selector->predicates[] = new Predicate('LocationName', 'EQUALS', $locationNames);

		// Only one locale can be used in a request.
		$selector->predicates[] = new Predicate('Locale', 'EQUALS', $locale);

		// Make the get request.
		$locationCriteria = $locationCriterionService->get($selector);

		// Display results.
		if (isset($locationCriteria) && count($locationCriteria) === 1) {
			foreach ($locationCriteria as $locationCriterion) {
				$location = $locationCriterion->location->id;
			}
		} elseif (isset($locationCriteria) && count($locationCriteria) > 1) {
			$locationCriterion = $locationCriteria[0];

            if (count($locationCriterion->location->parentLocations) == 2 &&
                $locationCriterion->location->displayType == 'City' &&
                $locationCriterion->location->parentLocations[1]->id == 2276)
            {
                $location = $locationCriterion->location->id;
            }
            else if ($locationCriterion->location->parentLocations[0]->displayType == 'State') {
                $location = $locationCriterion->location->parentLocations[0]->id;
            }
		}
		
		return $location;
	}

    /**
     * @param $data
     * @param $location
     * @return mixed
     */
    public function get($data, $location)
	{
		$user = new AdWordsUser();
		$user->LogAll();

		$location = $this->getLocation($user, $location);
		$location = is_null($location) ? 2276 : $location;

		foreach ($data as $k => $value) {
			if ($value) {
				$data[$k] = array(
					'keyword' => $value,
					'total_de' => $this->call($user, $value)[0],
					'total_reg' => $this->call($user, $value, $location)[0]
				);
			}
		}

		return $data;
	}
}
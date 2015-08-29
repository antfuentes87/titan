<?php
namespace framework;

class ENCschema{
	/*Setup*/
	const URL = 'http://schema.org/';
	const itemScope = 'itemscope';
	const itemType = 'itemtype';
	
	/*Item Types & Item Props*/
	const personAuthor = '{"itemprop":"author", "itemtype":"Person"}';
	const videoVideoObject = '{"itemprop":"video", "itemtype":"VideoObject"}';
	const associatedMediaMediaObject = '{"itemprop":"associatedMedia", "itemtype":"MediaObject"}';
	const imageImageObject = '{"itemprop":"image", "itemtype":"ImageObject"}';
	
	/*Item Types*/
	const blog = '{"itemtype":"Blog"}';
	const blogPosting = '{"itemtype":"BlogPosting"}';
	const imageObject = '{"itemtype":"ImageObject"}';
	const person = '{"itemtype":"Person"}';
	const videoObject = '{"itemtype":"VideoObject"}';
	const imageGallery = '{"itemtype":"ImageGallery"}';

	/*Item Props*/
	const associatedMedia = '{"itemprop":"associatedMedia"}';
	const datePublished = '{"itemprop":"datePublished"}';
	const headline = '{"itemprop":"headline"}';
	const author = '{"itemprop":"author"}';
	const url = '{"itemprop":"url"}';
	const name = '{"itemprop":"name"}';
	const articleBody = '{"itemprop":"articleBody"}';
	const description = '{"itemprop":"description"}';
	const video = '{"itemprop":"video"}';
	const image = '{"itemprop":"image"}';

	const caption = '{"itemprop":"caption"}';
	const thumbnailUrl = '{"itemprop":"thumbnailUrl"}';
	const contentURL = '{"itemprop":"contentURL"}';
	const contentUrl = '{"itemprop":"contentURL"}';
	const embedURL = '{"itemprop":"embedURL"}';
	const uploadDate = '{"itemprop":"uploadDate"}';
	const width = '{"itemprop":"width"}';
	const height = '{"itemprop":"height"}';
	const duration = '{"itemprop":"duration"}';
}
?>
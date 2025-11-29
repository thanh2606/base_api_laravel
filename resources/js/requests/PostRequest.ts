export interface StorePostRequestInterface {
	title: null|string,
	slug: null|string,
	desc: null|string,
	content: null|string,
	status: number,
	category_ids?: number[],
	tag_ids?: number[],
	image_id: null|number,
	image: null|string,
	meta_title: null|string,
	meta_keywords: null|string,
	meta_desc: null|string,
}

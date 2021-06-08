local testframework = require 'Module:TestFramework'

local PRP = require 'mw.ext.proofreadPage'

--[=[
Return the title from the give ntitle or page name
]=]
local function testGetIndexTitle( text_or_id )
	local index = PRP.newIndex( text_or_id )
	return index.title.fullText
end

--[=[
Check that the index title is immutable
]=]
local function testGetIndexTitleMutation( text_or_id )
	local index = PRP.newIndex( text_or_id )
	local newTitle = mw.title.makeTitle( PRP.NS_INDEX, "SomethingElse" )

	local function setTitle()
		index.title = newTitle
	end

	local success, ret = pcall( setTitle )
	return success
end

local function testIndexGetPage( text_or_id, n )
	local index = PRP.newIndex( text_or_id )
	local page = index:getPage( n )

	return page and page.title.fullText or nil
end

--[=[
Return the quality counts from the given title or page name
]=]
local function testGetQualityLevelCounts( text_or_id )
	local index = PRP.newIndex( text_or_id )

	-- combine the various fields into one table for easy testing
	return {
		missing = index.missingPages,
		existing = index.existingPages,
		total = index.pageCount,
		[ '0' ] = index:pagesWithLevel( 0 ),
		[ '1' ] = index:pagesWithLevel( 1 ),
		[ '2' ] = index:pagesWithLevel( 2 ),
		[ '3' ] = index:pagesWithLevel( 3 ),
		[ '4' ] = index:pagesWithLevel( 4 )
	}
end

--[=[
Return the pagination from the given title or page name
]=]
local function testGetPagination( text_or_id )
	local index = PRP.newIndex( text_or_id )
	return index.pagination
end

--[=[
Return the title from the given title or page name
]=]
local function testGetPageTitle( text_or_id )
	local page = PRP.newPage( text_or_id )
	return page.title.fullText
end

--[=[
Return the index title from the given title or page name
]=]
local function testGetPageIndex( text_or_id )
	local page = PRP.newPage( text_or_id )

	if not page.index or not page.index.title then
		return 'not found'
	end
	return page.index.title.fullText
end

--[=[
Return the quality table from the given title or page name
]=]
local function testGetPageQuality( text_or_id )
	local page = PRP.newPage( text_or_id )
	-- combine the various fields into one table for easy testing
	return {
		level = page.quality
	}
end

--[=[
Return the page with the given offset from another
]=]
local function testPageWithOffset( page_text_or_id, n )
	local page = PRP.newPage( page_text_or_id )
	local otherPage = page:withOffset( n )

	return otherPage and otherPage.title.fullText or 'not found'
end

--[=[
Return the page numbering values
]=]
local function testPageNumbering( page_text_or_id )
	local page = PRP.newPage( page_text_or_id )

	if not page then
		return 'not found'
	end

	return {
		page = page.title.fullText,
		position = page.position,
		raw = page.rawNumber,
		displayed = page.displayedNumber
	}
end

local tests = {
	{
		name = 'Verify that we can get Indexes from title strings',
		func = testGetIndexTitle,
		args = { 'Foobar.djvu' },
		expect = { 'Index:Foobar.djvu' }
	},
	{
		name = 'Verify that we can set Index titles from title objects',
		func = testGetIndexTitle,
		args = { mw.title.makeTitle( 'Index', 'Foobar.djvu' ) },
		expect = { 'Index:Foobar.djvu' }
	},
	{
		name = 'Verify that mutating the title object does not trash the other functions',
		func = testGetIndexTitleMutation,
		args = { 'Foobar.djvu' },
		expect = { false }
	},
	{
		name = 'Non-existing index has no pagination',
		func = testGetPagination,
		args = { 'non-existent index' },
		expect = { nil },
	},
	{
		name = 'Get page from index by index',
		func = testIndexGetPage,
		args = { 'Foobar', 2 },
		expect = { 'Page:Test 2.jpg' },
	},
	{
		name = 'Verify that we can get the quality counts for an index',
		func = testGetQualityLevelCounts,
		args = { 'Foobar' }, -- index with 6 "image-based" pages
		expect = {
			{
				[ "existing" ] = 5,
				[ "missing" ] = 1,
				[ "total"  ] = 6,
				[ '0' ] = 1,
				[ '1' ] = 1,
				[ '2' ] = 1,
				[ '3' ] = 2,
				[ '4' ] = 0
			},
		}
	},
	{
		name = 'Non-existing index has no quality stats',
		func = testGetQualityLevelCounts,
		args = { 'non-existent index' },
		expect = {
			{
				[ "existing" ] = 0,
				[ "missing" ] = 0,
				[ "total"  ] = 0,
				[ '0' ] = 0,
				[ '1' ] = 0,
				[ '2' ] = 0,
				[ '3' ] = 0,
				[ '4' ] = 0
			},
		}
	},
	{
		name = 'Verify that we can get Pages from title strings',
		func = testGetPageTitle,
		args = { 'Test 1.jpg' }, -- one of the existing pages
		expect = { 'Page:Test 1.jpg' }
	},
	{
		name = 'Verify that we can get indexes from Page titles',
		func = testGetPageIndex,
		args = { 'Test 1.jpg' }, -- one of the existing pages
		expect = { 'Index:Foobar' }
	},
	{
		name = 'Verify that a page that has no index does not have an index',
		func = testGetPageIndex,
		args = { 'Unconnected page.jpg' },
		expect = { 'not found' }
	},
	{
		name = 'Verify that a page that doesn\'t exist does not have an index',
		func = testGetPageIndex,
		args = { 'Non-existent page.jpg' },
		expect = { 'not found' }
	},
	{
		name = 'Verify that we can get page quality info',
		func = testGetPageQuality,
		args = { 'Test 1.jpg' }, -- one of the existing pages
		expect = {
			{
				level = 0,
				-- user = 'Test User'
			},
		}
	},
	{
		name = 'Get page offset by 1',
		func = testPageWithOffset,
		args = { 'Test 3.jpg', 1 },
		expect = { 'Page:Test 4.jpg' },
	},
	{
		name = 'Get page offset by -1',
		func = testPageWithOffset,
		args = { 'Test 3.jpg', -1 },
		expect = { 'Page:Test 2.jpg' },
	},
	{
		name = 'Get page offset by -1 that will fail',
		func = testPageWithOffset,
		args = { 'Test 1.jpg', -1 },
		expect = { 'not found' },
	},
	{
		name = 'Get non-existing page offset by index',
		func = testPageWithOffset,
		args = { 'Non-existent page.jpg', 1 },
		expect = { 'not found' },
	},
	{
		name = 'Get page numbering (matching the link text set in the index template)',
		func = testPageNumbering,
		args = { 'Test 1.jpg' },
		expect = {
			{
				page = 'Page:Test 1.jpg',
				raw = '2',
				displayed = '2',
				position = 1
			}
		},
	},
	{
		name = 'Get page numbering for missing page',
		func = testPageNumbering,
		args = { 'not a page.jpg' },
		expect = { {
			page = 'Page:Not a page.jpg',
		}
		},
	},
}

return testframework.getTestProvider( tests )

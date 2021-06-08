--[=[
Main ProofreadPage Lua module
]=]

local php

local util = require 'libraryUtil'

local proofreadPage = {} -- this is the package

function proofreadPage.setupInterface( options )
	-- Boilerplate
	proofreadPage.setupInterface = nil
	php = mw_interface
	mw_interface = nil

	-- Install into the mw global
	mw = mw or {}
	mw.ext = mw.ext or {}
	mw.ext.proofreadPage = proofreadPage

	-- proofreadPage namespace IDs
	proofreadPage.NS_INDEX = options.NS_INDEX
	proofreadPage.NS_PAGE = options.NS_PAGE

	-- convenient master reference of the proofread status levels
	proofreadPage.QualityLevel = options.qualityLevel;

	-- Do any other setup here
	package.loaded['mw.ext.proofreadPage'] = proofreadPage
end

--[=[
The Index Object represent an index-namespace page
]=]
function proofreadPage.newIndex( title )
	local obj = {}
	local checkSelf = util.makeCheckSelfFunction( 'mw.ext.proofreadPage',
		'index', obj, 'index object' );

	if type( title ) == 'string' then
		title = mw.title.makeTitle( proofreadPage.NS_INDEX, title )
	end

	assert( title, 'Index title is needed' )
	assert( title.namespace == proofreadPage.NS_INDEX,
		string.format( 'Title is not in the Index namespace: %s',
			title.fullText )
	)

	local data = {
		title = title,
	}

	--[=[
	Get the n'th page in the index (1 is the first)

	Returns a page object or nil if the index contains no such page
	]=]
	function data:getPage( n )
		checkSelf( self, 'getPage' )
		local page = php.doGetPageInIndex( title.text, tonumber( n ) )

		if not page then
			return nil
		end

		return proofreadPage.newPage( page )
	end

	local qualityStats
	local function cacheQualityStats()
		if qualityStats == nil then
			qualityStats = php.doGetIndexProgress( title.text )
		end
	end

	--[=[
	Get the number of pages with the given level
	]=]
	function data:pagesWithLevel( level )
		checkSelf( self, 'pagesWithLevel' )
		cacheQualityStats()
		return qualityStats[ level ]
	end

	return setmetatable( obj, {
		__eq = title.equals,
		__lt = title.__lt,
		__tostring = function ( t )
			return t.title.prefixedText
		end,
		__index = function ( t, k )

			if k == 'fields' then
				if data.fields == nil then
					data.fields = php.doGetIndexFields( title.text )
				end
				return data.fields
			end

			if k == 'categories' then
				if data.categories == nil then
					local cats = php.doGetIndexCategories( title.text )
					data.categories = {}
					for _, v in pairs( cats ) do
						table.insert( data.categories, mw.title.new( v, 'Category' ) )
					end
				end
				return data.categories
			end

			if k == 'missingPages' then
				cacheQualityStats()
				return qualityStats.missing
			end

			if k == 'existingPages' then
				cacheQualityStats()
				return qualityStats.existing
			end

			if k == 'pageCount' then
				if data.pageCount == nil then
					data.pageCount = php.doGetNumberOfPages( title.text )
				end
				return data.pageCount
			end

			return data[k]
		end,
		__newindex = function ( t, k, v )
			error( "index '" .. k .. "' is read only", 2 )
		end
	} )
end

--[=[
The Page object represents a Page namespace page.
]=]
function proofreadPage.newPage( title )
	local obj = {}
	local checkSelf = util.makeCheckSelfFunction( 'mw.ext.proofreadPage',
		'page', obj, 'page object' );

	if type( title ) == 'string' then
		title = mw.title.makeTitle( proofreadPage.NS_PAGE, title )
	end

	assert( title, 'Page title is needed' )

	if title.namespace ~= proofreadPage.NS_PAGE then
		error( string.format( 'Title is not in the Page namespace: %s',
			title.fullText ) )
	end

	local data = {
		title = title,
	}

	local private = {}

	--[=[
	Get the n'th page in the index from this page

	@param n the offset of the page (1 is next, -1 is prev)

	Returns a page object or nil if the index contains no such page
	]=]
	function data:withOffset( offset )
		checkSelf( self, 'withOffset' )

		-- if we request a page without an index, bail out
		-- this can cost up to 2 expensive functions
		if not obj.position or not obj.index then
			return nil
		end
		return obj.index:getPage( obj.position + offset )
	end

	local function cachePageNumbering()
		if private.numbering == nil then
			private.numbering = php.doGetPageNumbering( title.text )
		end
	end

	local function cachePageQuality()
		if private.quality == nil then
			private.quality = php.doGetPageQuality( title.text )
		end
	end

	return setmetatable( obj, {
		__eq = title.equals,
		__lt = title.__lt,
		__tostring = function ( t )
			return t.title.prefixedText
		end,
		__index = function ( t, k )

			if k == 'quality' then
				cachePageQuality()
				return private.quality.level
			end

			if k == 'index' then
				if data.index == nil then
					local indexTitle = php.doGetIndexForPage( title.text )

					if indexTitle then
						data.index = proofreadPage.newIndex( indexTitle )
					else
						-- didn't find it, but still cache the failure
						data.index = ''
					end
				end

				if data.index and data.index ~= '' then
					return data.index
				end

				return nil
			end

			if k == 'position' then
				cachePageNumbering()
				return private.numbering and private.numbering.position or nil
			end

			if k == 'displayedNumber' then
				cachePageNumbering()
				return private.numbering and private.numbering.display or nil
			end

			if k == 'rawNumber' then
				cachePageNumbering()
				return private.numbering and private.numbering.raw or nil
			end

			if k == 'nextPage' then
				return obj:withOffset( 1 )
			end

			if k == 'previousPage' then
				return obj:withOffset( -1 )
			end

			return data[k]
		end,
		__newindex = function ( t, k, v )
			error( "index '" .. k .. "' is read only", 2 )
		end
	} )
end

return proofreadPage

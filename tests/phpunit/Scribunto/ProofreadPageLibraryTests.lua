test = {}

local testframework = require 'Module:TestFramework'

local PRP = require 'mw.ext.proofreadPage'

local function testQualityLevels()
	return {
		PRP.QualityLevel['WITHOUT_TEXT'],
		PRP.QualityLevel['NOT_PROOFREAD'],
		PRP.QualityLevel['PROBLEMATIC'],
		PRP.QualityLevel['PROOFREAD'],
		PRP.QualityLevel['VALIDATED']
	}
end

local function textNamespaces()
	return {
		PRP.NS_INDEX,
		PRP.NS_PAGE,
	}
end

local tests = {
	{
		name = 'Test access to the quality level enum',
		func = testQualityLevels,
		args = {},
		expect = { { 0, 1, 2, 3 ,4 } }
	},
	{
		name = 'Test access to the NS constants',
		func = textNamespaces,
		args = {},
		expect = { { 252, 250 } }
	},
}

return testframework.getTestProvider( tests )

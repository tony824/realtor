# realtor
Search realtor and do some filtering


### API Reference/Options

* `Culture` - `1` for EN, `2` for FR. Defaulted to 1.
* `ApplicationId` - Unused. Defaulted to 1.
* `PropertySearchTypeId`- Defaulted to 1. Determines the type of property, possible values:
    * `0` No Preference
    * `1` Residential
    * `2` Recreational
    * `3` Condo/Strata
    * `4` Agriculture
    * `5` Parking
    * `6` Vacant Land
    * `8` Multi Family

*Most useful options*

* `PriceMin` - Defaults to 0
* `PriceMax`
* `LongitudeMin` - Designates the bounds of the query, easiest to find these values from browser requests.
* `LongitudeMax`
* `LatitudeMin`
* `LatitudeMax`
* `TransactionTypeId`- Defaults to 2?
    * `1` For sale or rent
    * `2` For sale
    * `3` For rent
* `StoreyRange` - ``"min-max"`` i.e. `"2-3"`
* `BedRange` - `"min-max"`
* `BathRange` - `"min-max"`

*Others*

* Sorting:

Type | `SortBy` | `SortOrder`
---- | -------- | -----------
Low to High ($) | `1` | `A`
High to Low ($) | `1` | `D`
Date Posted: New to Old | `6` | `D`
Date Posted: Old to New | `6` | `A`
Open Houses First | `12` | `D`
More Photos First | `13` | `D`
Virtual Tour First | `11` | `D`

* `viewState` - `m`, `g`, or `1`. Seems irrelevant.
* `Longitude` - Longitude to focus on? Unneeded
* `Latitude` - Latitude to focus on? Unneeded
* `ZoomLevel` - not sure what this does
* `CurrentPage` - read somewhere that it maxes at 51
* `RecordsPerPage`
* `MaximumResults`
* `PropertyTypeGroupID` - ???
* `OwnershipTypeGroupId`
    * `0` Any
    * `1` Freehold
    * `2` Condo/Strata
    * `3` Timeshare/Fractional
    * `4` Leasehold
* `ViewTypeGroupId`
    * `0` Any
    * `1` City
    * `2` Mountain
    * `3` Ocean
    * `4` Lake
    * `5` River
    * `6` Ravine
    * `7` Other
    * `8` All Water Views
* `BuildingTypeId`
    * `0` Any
    * `1` House
    * `2` Duplex
    * `3` Triplex
    * `5` Residential Commercial Mix
    * `6` Mobile Home
    * `12` Special Purpose
    * `14` Other
    * `16` Row/Townhouse
    * `17` Apartment
    * `19` Fourplex
    * `20` Garden Home
    * `27` Manufactured Home/Mobile
    * `28` Commercial Apartment
    * `29` Manufactured Home
* `ConstructionStyleId`
    * `0` Any
    * `1` Attached
    * `3` Detached
    * `5` Semi-detached
    * `7` Stacked
    * `9` Link
* `AirCondition`- `0` or `1`, defaults 0
* `Pool` - `0` or `1`, defaults 0
* `Fireplace` - `0` or `1`, defaults 0
* `Garage` - `0` or `1`, defaults 0
* `Waterfront` - `0` or `1`, defaults 0
* `Acreage` - `0` or `1`, defaults 0
* `Keywords` - search text

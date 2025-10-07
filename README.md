# iq_events

The iq_events module provides comprehensive event management functionality for Drupal sites, offering tools for creating, managing, and displaying events with advanced scheduling capabilities.

## Features

### Event Content Type
- **Custom Event Content Type**: Provides an `iq_event` content type specifically designed for event management
- **Integration with Content Management**: Events appear in the main content overview alongside other content types (pages, etc.)
- **Multi-language Support**: Full support for multilingual event content with proper translation handling

### Event Scheduling & Management
- **Advanced Date/Time Handling**: Built on Drupal's datetime and datetime_range modules for flexible event scheduling
- **Office Hours Integration**: Supports complex scheduling patterns through the office_hours module
- **Event Status Management**: Full publication workflow with published/unpublished states

### Address & Location Support
- **Address Management**: Integrated with the address module for comprehensive location data
- **Address Formatting**: Enhanced address display capabilities through address_formatter integration
- **Geographic Information**: Support for venue and location details

### SEO & Metadata
- **SEO Optimization**: Full integration with Yoast SEO for event-specific SEO management
- **Meta Tags Support**: Comprehensive meta tag support for better search engine visibility
- **Search Integration**: Events are indexed and searchable through the site's search functionality

### Integration Features
- **Pagedesigner Compatible**: Full integration with the Pagedesigner ecosystem for advanced layout and content management
- **Views Integration**: Events can be displayed in custom views and listings
- **Block System Integration**: Event information can be displayed through Drupal's block system
- **Calendar Integration**: Works with calendar modules for calendar-based event displays

### Content Management
- **Rich Content Support**: Events support rich content creation with text formatting, media embedding, and file attachments
- **Taxonomy Support**: Categorization and tagging capabilities for event organization
- **Media Integration**: Support for images, documents, and other media types in event content
- **Frontend Publishing**: Integration with frontend publishing workflows

### Technical Features
- **Modern Architecture**: Built for Drupal 9, 10, and 11 compatibility
- **Performance Optimized**: Efficient database queries and caching strategies
- **API Ready**: RESTful API support for headless implementations
- **Extensible**: Hook system and plugin architecture for custom extensions

## Installation

### Drupal 9 & 10:

Install the module as usual:

```bash
composer require iqual/iq_events
drush en iq_events
```

### Drupal 11:

The module includes compatibility for Drupal 11. Some dependencies may require the lenient composer plugin:

```bash
composer config minimum-stability dev
composer require mglaman/composer-drupal-lenient
composer config --merge --json extra.drupal-lenient.allowed-list '["drupal/address_formatter"]'
composer require iqual/iq_events
drush en iq_events
```

## Configuration

After installation:

1. Navigate to **Structure > Content Types** to configure the Event content type
2. Set up event fields and display settings as needed
3. Configure event-specific permissions at **People > Permissions**
4. Customize event displays through **Structure > Views**

## Usage

### Creating Events
1. Go to **Content > Add content > Event**
2. Fill in event details including title, description, dates, and location
3. Configure SEO settings using the integrated Yoast SEO tools
4. Set publication status and save

### Managing Events
- Use the content overview page to manage all events
- Filter and sort events by date, status, and other criteria
- Bulk operations available for managing multiple events

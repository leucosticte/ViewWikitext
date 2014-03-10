
<?php
if ( !defined( 'MEDIAWIKI' ) ) {
   die( 'This file is a MediaWiki extension. It is not a valid entry point' );
}

class SpecialViewWikitext extends SpecialPage {
   function __construct( ) {
      parent::__construct( 'ViewWikitext' );
   }

   function execute( $par ) {
      $this->setHeaders();
      $output = $this->getOutput();
      if ( !$par ) {
         $output->addWikiMsgArray( 'viewwikitext-notitleselected', $par );
      } else {
         $title = Title::newFromText( $par );
         if ( !$title ) {
            $output->addWikiMsgArray( 'viewwikitext-badtitle', $par );
         } else {
            $revision = Revision::newFromTitle( $title );
            if ( !$revision ) {
               $output->addWikiMsgArray( 'viewwikitext-badtitle', $par );
            } else {
               $outputText = "'''<big>";
               $outputText .= wfMessage( 'viewwikitext-intro', $par )->plain();
               $outputText .= "</big>'''<br/><br/>";
               $output->addWikitext( $outputText );
               $content = $revision->getContent( Revision::FOR_THIS_USER, $this->getUser() );
               $text = ContentHandler::getContentText( $content );
               $output->addHTML( htmlentities( $text ) );
            }
         }
      }
      return;
   }

   function getRobotPolicy() {
      global $wgViewWikitextRobotPolicy;
      return $wgViewWikitextRobotPolicy;
   }
}

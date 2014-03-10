
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
      global $wgScript;
      $request = $this->getRequest();
      if ( !$par ) {
         $par = $request->getVal( 'page' );
      }
      $this->getOutput()->addHTML(
         Html::openElement( 'form', array( 'method' => 'get', 'action' => $wgScript, 'name' => 'uluser', 'id' => 'viewwikitext-form1' ) ) .
         Html::hidden( 'title', $this->getPageTitle()->getPrefixedText() ) .
         Xml::fieldset( $this->msg( 'viewwikitext-lookup-page' )->text() ) .
         Xml::inputLabel( $this->msg( 'viewwikitext-page-lookup' )->text(), 'page', 'username', 30, str_replace( '_', ' ', $par ), array( 'autofocus' => true ) ) . ' ' .
         Xml::submitButton( $this->msg( 'htmlform-submit' )->text() ) .
         Html::closeElement( 'fieldset' ) .
         Html::closeElement( 'form' ) . "\n"
      );
      if ( $par ) {
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
               $pageLang = $title->getPageLanguage();
               $params = array(
               'id' => 'wpTextbox1',
               'name' => 'wpTextbox1',
               'cols' => $this->getUser()->getOption( 'cols' ),
               'rows' => $this->getUser()->getOption( 'rows' ),
               'readonly' => 'readonly',
               'lang' => $pageLang->getHtmlCode(),
               'dir' => $pageLang->getDir(),
               );
               $output->addHTML( Html::element( 'textarea', $params, $text ) );
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

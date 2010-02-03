<?php
class mailbox_ViewMessageSuccessView extends f_view_BaseView
{
	/**
	 * @param Context $context
	 * @param Request $request
	 */
	public function _execute($context, $request)
	{
		$this->setTemplateName('Mailbox-ViewMessage', K::HTML);
		
		// Module backoffice styles :
		$this->setAttribute(
           'cssInclusion',
           $this->getStyleService()
	    	  ->registerStyle('modules.dashboard.dashboard')
	    	  ->execute(K::HTML)
	    );
		
		$this->setAttribute('message', $request->getAttribute('document'));
	}
}
<?php
// Make sure you have `dompdf/dompdf` in your composer dependencies.
            PhpOffice\PhpWord\Settings::setPdfRendererName(PhpOffice\PhpWord\Settings::PDF_RENDERER_DOMPDF);
            // Any writable directory here. It will be ignored.
            PhpOffice\PhpWord\Settings::setPdfRendererPath('.');
            PhpOffice\PhpWord\Settings::setTempDir(__DIR__.'/../../');

            //$phpword = new \PhpOffice\PhpWord\PhpWord();    
            //$phpword->loadTemplate('file/'.$export_file,'');
            $pdf_file = str_replace('.docx','.pdf',$export_file);
            
            try {
				
				$phpword = PhpOffice\PhpWord\IOFactory::load(__DIR__.'/../../file/'.$export_file); 
			} catch (Exception $e) {
				echo 'Caught exception: ',  $e->getMessage(), "\n";
				die;
			}
			$sections = $phpword->getSections();
			
			foreach($sections as $id=>$sec)
			{
				echo "<div><p>Section $id</p><p>";
				foreach($sec->getElements() as $j=>$ele)
				{
					if ($ele instanceof \PhpOffice\PhpWord\Element\TextRun) 
					{
						echo "<div><p>Element $j</p><p>";
						$secondSectionElement = $ele->getElements();
						foreach ($secondSectionElement as $secondSectionElementKey => $secondSectionElementValue) 
						{
							if ($secondSectionElementValue instanceof \PhpOffice\PhpWord\Element\Text) 
							{
								echo $secondSectionElementValue->getText() . '<br/>';
							} else if ($secondSectionElementValue instanceof \PhpOffice\PhpWord\Element\TextRun )
							{
								$thirdElements = $secondSectionElementValue->getElements();
								foreach ($thirdElements as $k => $thirdValue) 
								{
									if ($thirdValue instanceof \PhpOffice\PhpWord\Element\Text) 
									{
										echo $thirdValue->getText() . '<br/>';
									}
								}
							} else
							if ($ele instanceof \PhpOffice\PhpWord\Element\Table) echo '2nd table <br>';
						}
					}
					else if ($ele instanceof \PhpOffice\PhpWord\Element\Table) 
					{
						foreach ($ele->getRows() as $r=>$row)
						{
							foreach ($row->getCells() as $c=>$cell)
							{
								foreach ($cell->getElements() as $n=>$table_ele) 
								{
									if ($table_ele instanceof \PhpOffice\PhpWord\Element\Text) 
									{
										echo $table_ele->getText() . '<br/>';
									} else if ($table_ele instanceof \PhpOffice\PhpWord\Element\TextRun) 
									{
										foreach ($table_ele->getElements() as $m=>$tr) 
										{
											if ($tr instanceof \PhpOffice\PhpWord\Element\Text) 
											{
												echo $r.':'.$c.':'.$n.':'.$m.'. '.$tr->getText() . '<br/>';
											}											
										}
									}
								}
							}
							echo '<br>';
						}
					}
				}
				echo "</p></div>";
			}
			exit;

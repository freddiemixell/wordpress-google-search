import SearchResults from './SearchResults';
import SearchBox from './SearchBox';
import SearchInfo from './SearchInfo';
import Pagination from './Pagination';

import withData from './withData';

const { Fragment } = wp.element;

function App(props) {
  return (
    <Fragment>
      <SearchInfo {...props} />
      <SearchBox {...props} />
      <SearchResults {...props} />
      <Pagination {...props} />
    </Fragment>
  );
}

export default withData(App);

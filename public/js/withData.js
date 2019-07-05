var urlParams = new URLSearchParams(window.location.search);
const Component = wp.element.Component;

function withData(WrappedComponent) {
  return class extends Component {
    constructor(props) {
      super(props);

      this.state = {
        query: null,
        results: null,
        handleChange: this.handleChange.bind(this),
        searchSite: this.searchSite.bind(this),
        error: null,
      }

      this.searchSite = this.searchSite.bind(this);
    }

    componentDidMount() {
      const query = urlParams.get('query');
      fetch(`/wp-json/fm-google-site-search/v1/search_site/?query=${query}`)
        .then(res => res.json())
        .then(results => this.setState({results, query}))
        .catch(error => this.setState({error}))
    }

    async handleChange(event) {
      await this.setState({query: event.target.value});
    }

    searchSite(event) {
      event.preventDefault();
      const { query } = this.state;
      fetch(`/wp-json/fm-google-site-search/v1/search_site/?query=${query}`)
      .then(res => res.json())
      .then(results => this.setState({results, query}))
      .catch(error => this.setState({error}))
    }

    debounce(func, wait, immediate) {
      var timeout;
      return function() {
        var context = this, args = arguments;
        var later = function() {
          timeout = null;
          if (!immediate) func.apply(context, args);
        };
        var callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func.apply(context, args);
      };
    };

    render() {
      return <WrappedComponent {...this.state} />;
    }
  }
}

export default withData;

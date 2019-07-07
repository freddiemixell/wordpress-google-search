var urlParams = new URLSearchParams(window.location.search);
const { Component } = wp.element;

function withData(WrappedComponent) {
  return class extends Component {
    constructor(props) {
      super(props);

      this.state = {
        query: null,
        results: null,
        start: null,
        handleChange: this.handleChange.bind(this),
        searchSite: this.searchSite.bind(this),
        setIndex: this.setIndex.bind(this),
        error: null,
        loading: false,
      }
    }

    componentDidMount() {
      const query = urlParams.get('query');
      if (query === null) {
        this.setState({error: 1});
        return;
      }
      this.setState({loading: true});
      fetch(`/wp-json/fm-google-site-search/v1/search_site/?query=${query}`)
        .then(res => res.json())
        .then(results => this.setState({results, query, error: null, loading: false}))
        .catch(() => this.setState({error: 2}))
    }

    async handleChange(event) {
      await this.setState({query: event.target.value});
    }

    setIndex(index) {
      this.setState({start: index}, () => this.searchSite());
    }

    searchSite(event) {
      if (typeof event !== 'undefined') {
        event.preventDefault();
      }
      const { query, start } = this.state;
      if (query === null) {
        this.setState({error: 1});
        return;
      }

      this.setState({loading: true});

      fetch(`/wp-json/fm-google-site-search/v1/search_site/?query=${query}${start !== null ? "&start=" + start : ""}`)
      .then(res => res.json())
      .then(results => this.setState({results, query, error: null, loading: false}))
      .catch(() => this.setState({error: 2}))
    }

    render() {
      return <WrappedComponent {...this.state} />;
    }
  }
}

export default withData;
